from pathlib import Path

path = Path("/home/karl/data/nginx/default.conf")
text = path.read_text()

old_build = '''    location ^~ /build/ {
        add_header Cache-Control "public, max-age=31536000, immutable";
        try_files $uri =404;
    }'''

new_build = '''    location ^~ /build/ {
        add_header Cache-Control "public, max-age=31536000, immutable";
        add_header CDN-Cache-Control "public, max-age=31536000, immutable";
        try_files $uri =404;
    }'''

old_img = '''    location ^~ /img/ {
        add_header Cache-Control "public, max-age=2592000";
        try_files $uri =404;
    }'''

new_img = '''    location ^~ /img/ {
        add_header Cache-Control "public, max-age=2592000, stale-while-revalidate=86400";
        add_header CDN-Cache-Control "public, max-age=2592000, stale-while-revalidate=86400";
        try_files $uri =404;
    }'''

if old_build not in text:
    raise SystemExit("build location block not found or already patched")
if old_img not in text:
    raise SystemExit("img location block not found or already patched")

path.write_text(text.replace(old_build, new_build, 1).replace(old_img, new_img, 1))
print("patched /build/ and /img/ CDN-Cache-Control")
