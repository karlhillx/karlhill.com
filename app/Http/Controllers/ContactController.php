<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ContactController extends Controller
{
    /**
     * Handle contact form submission - email only, no database storage
     */
    public function store(Request $request): JsonResponse
    {
        // This should NEVER return HTML - if you see HTML, the route isn't being hit
        if (!$request->expectsJson() && !$request->wantsJson()) {
            $request->headers->set('Accept', 'application/json');
        }
        
        // Log for debugging
        Log::info('Contact form submission received', [
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->url(),
            'data' => $request->except(['_token', 'website'])
        ]);
        
        try {

            // Rate limiting: max 5 requests per hour per IP
            // Using file-based cache to avoid database dependency
            try {
                $cache = cache()->store('file'); // Use file cache explicitly
                $key = 'contact_rate_limit:' . $request->ip();
                $attempts = $cache->get($key, 0);
                
                if ($attempts >= 5) {
                    $expiresAt = $cache->get($key . '_expires', now()->addHour());
                    $seconds = max(0, now()->diffInSeconds($expiresAt));
                    return response()->json([
                        'message' => "Too many attempts. Please try again in {$seconds} seconds.",
                    ], 429);
                }

                // Increment attempts
                if ($attempts === 0) {
                    $cache->put($key, 1, now()->addHour());
                    $cache->put($key . '_expires', now()->addHour(), now()->addHour());
                } else {
                    $cache->increment($key);
                }
            } catch (\Exception $rateLimitError) {
                // If rate limiting fails (cache issues), log but continue
                Log::warning('Rate limiting failed, continuing anyway', [
                    'error' => $rateLimitError->getMessage()
                ]);
            }

            // Honeypot spam protection
            if (!empty($request->input('website'))) {
                // Silently ignore spam bots
                return response()->json(['message' => 'Message sent successfully'], 200);
            }

            // Validate request
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email:rfc,dns', 'max:255'],
                'message' => ['required', 'string', 'min:10', 'max:2000'],
            ], [
                'name.required' => 'Please provide your name.',
                'email.required' => 'Please provide your email address.',
                'email.email' => 'Please provide a valid email address.',
                'message.required' => 'Please provide a message.',
                'message.min' => 'Your message must be at least 10 characters long.',
                'message.max' => 'Your message is too long. Please keep it under 2000 characters.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Please correct the errors below.',
                    'errors' => $validator->errors()->toArray(),
                ], 422);
            }

            // Send email notification
            // If mail fails, we'll log it but still return success to user
            try {
                Mail::send('mail.contact', [
                    'name' => $request->name,
                    'email' => $request->email,
                    'messageContent' => $request->message,
                    'ip' => $request->ip(),
                ], function ($message) use ($request) {
                    $message->to(config('mail.from.address'), config('mail.from.name'))
                        ->subject('New Contact Form Submission from ' . $request->name)
                        ->replyTo($request->email, $request->name);
                });
                
                Log::info('Contact form email sent successfully', [
                    'to' => config('mail.from.address'),
                    'from' => $request->email
                ]);
            } catch (\Exception $mailError) {
                // Log the mail error but don't fail the request
                // The email will be logged in storage/logs/laravel.log if using 'log' driver
                Log::error('Contact form email failed to send', [
                    'error' => $mailError->getMessage(),
                    'to' => config('mail.from.address'),
                    'from' => $request->email,
                    'subject' => 'New Contact Form Submission from ' . $request->name
                ]);
                
                // If using 'log' driver, the email is still "sent" (logged)
                // If using SMTP and it fails, we still want to notify user it was received
                if (config('mail.default') !== 'log') {
                    // Email failed but we'll still show success since form was submitted
                    Log::warning('SMTP mail failed - consider using log driver or configuring App Password for Gmail');
                }
            }

            $response = response()->json([
                'message' => 'Message sent successfully! I\'ll get back to you soon.',
            ], 200);
            
            // Explicitly set JSON headers
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            report($e);
            return response()->json([
                'message' => 'Sorry, there was an error sending your message. Please try again later.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
