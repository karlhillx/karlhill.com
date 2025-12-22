@php
    $email = 'karlhillx@gmail.com';
@endphp

<div x-data="contactModal" x-cloak>
    <!-- Contact Modal -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.away="close()"
         @keydown.escape.window="close()"
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         style="display: none;">
        <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden"
             @click.stop>
            <!-- Modal Header -->
            <div class="relative px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-cyan-50 dark:from-gray-800 dark:to-gray-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-500 to-cyan-500 flex items-center justify-center">
                            <i class="far fa-paper-plane text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Get in Touch</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">I'd love to hear from you</p>
                        </div>
                    </div>
                    <button @click="close()" 
                            type="button"
                            class="p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Success Message -->
                <div x-show="success" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-green-800 dark:text-green-300 font-medium">
                            Message sent successfully! I'll get back to you soon.
                        </p>
                    </div>
                </div>

                <!-- Error Message -->
                <div x-show="error" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-red-800 dark:text-red-300 font-medium" x-text="error"></p>
                    </div>
                </div>

                <!-- Contact Form -->
                <form @submit.prevent="submit()" x-show="!success">
                    <!-- Honeypot Field -->
                    <input type="text" 
                           x-model="form.website" 
                           name="website" 
                           autocomplete="off"
                           tabindex="-1"
                           class="sr-only"
                           aria-hidden="true">

                    <!-- Name Field -->
                    <div class="mb-4">
                        <label for="contact-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="contact-name"
                               x-model="form.name"
                               required
                               :class="errors.name ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500'"
                               class="w-full px-4 py-3 rounded-lg border bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 transition-colors"
                               placeholder="Your name">
                        <p x-show="errors.name" class="mt-1 text-xs text-red-600 dark:text-red-400" x-text="errors.name"></p>
                    </div>

                    <!-- Email Field -->
                    <div class="mb-4">
                        <label for="contact-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="contact-email"
                               x-model="form.email"
                               required
                               :class="errors.email ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500'"
                               class="w-full px-4 py-3 rounded-lg border bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 transition-colors"
                               placeholder="your.email@example.com">
                        <p x-show="errors.email" class="mt-1 text-xs text-red-600 dark:text-red-400" x-text="errors.email"></p>
                    </div>

                    <!-- Message Field -->
                    <div class="mb-6">
                        <label for="contact-message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Message <span class="text-red-500">*</span>
                        </label>
                        <textarea id="contact-message"
                                  x-model="form.message"
                                  required
                                  rows="5"
                                  :class="errors.message ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500'"
                                  class="w-full px-4 py-3 rounded-lg border bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 transition-colors resize-none"
                                  placeholder="Tell me about your project or how I can help..."></textarea>
                        <p x-show="errors.message" class="mt-1 text-xs text-red-600 dark:text-red-400" x-text="errors.message"></p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            :disabled="submitting"
                            class="w-full bg-gradient-to-r from-indigo-600 to-cyan-600 hover:from-indigo-700 hover:to-cyan-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-2">
                        <svg x-show="submitting" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="submitting ? 'Sending...' : 'Send Message'"></span>
                    </button>
                </form>

                <!-- Alternative Contact Info -->
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-center text-gray-500 dark:text-gray-400 mb-3">
                        Or reach me directly at:
                    </p>
                    <div class="flex items-center justify-center gap-2">
                        <i class="far fa-envelope text-gray-400"></i>
                        <a href="mailto:{{ $email }}" 
                           class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium break-all">
                            {{ $email }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

