import { prefersReducedMotion } from '../lib/prefs.js';

const WGSL = /* wgsl */ `
struct Uniforms {
  time: f32,
  _pad: f32,
  resolution: vec2f,
}

@group(0) @binding(0) var<uniform> u: Uniforms;

@vertex
fn vs(@builtin(vertex_index) i: u32) -> @builtin(position) vec4f {
  var pos = array<vec2f, 3>(vec2f(-1.0, -1.0), vec2f(3.0, -1.0), vec2f(-1.0, 3.0));
  return vec4f(pos[i], 0.0, 1.0);
}

fn hash(p: vec2f) -> f32 {
  return fract(sin(dot(p, vec2f(127.1, 311.7))) * 43758.5453);
}

fn noise(p: vec2f) -> f32 {
  let i = floor(p);
  let f = fract(p);
  let u = f * f * (3.0 - 2.0 * f);
  return mix(
    mix(hash(i), hash(i + vec2f(1.0, 0.0)), u.x),
    mix(hash(i + vec2f(0.0, 1.0)), hash(i + vec2f(1.0, 1.0)), u.x),
    u.y
  );
}

fn fbm(p: vec2f) -> f32 {
  var v = 0.0;
  var a = 0.5;
  var q = p;
  for (var i = 0; i < 5; i++) {
    v += a * noise(q);
    q *= 2.03;
    a *= 0.5;
  }
  return v;
}

@fragment
fn fs(@builtin(position) frag: vec4f) -> @location(0) vec4f {
  let res = max(u.resolution, vec2f(1.0));
  let uv = frag.xy / res;
  let t = u.time;
  let terrain = fbm(uv * vec2f(3.4, 4.2) + vec2f(0.15, -0.08));
  let swell = fbm(uv * 5.5 + vec2f(t * 0.07, t * 0.045));
  let river = smoothstep(0.46, 0.58, terrain + (uv.y - 0.2) * 0.22);
  let flood = smoothstep(0.38, 0.62, swell * 0.65 + river * 0.55);
  let land = vec3f(0.42, 0.46, 0.36);
  let water = vec3f(0.10, 0.38, 0.58);
  let inundation = vec3f(0.18, 0.72, 0.86);
  var color = mix(land, water, river);
  color = mix(color, inundation, flood * 0.72);
  let shore = smoothstep(0.02, 0.0, abs(river - 0.5));
  color = mix(color, vec3f(0.92, 0.72, 0.28), shore * 0.35);
  let glint = pow(max(swell, 0.0), 6.0) * flood;
  color += vec3f(0.55, 0.75, 0.85) * glint * 0.25;
  let vignette = smoothstep(1.15, 0.28, length(uv - vec2f(0.5)));
  color *= 0.55 + 0.45 * vignette;
  return vec4f(color, 1.0);
}
`;

function gpuUsage() {
    const buffer = globalThis.GPUBufferUsage;
    const texture = globalThis.GPUTextureUsage;
    return {
        uniform: buffer ? buffer.UNIFORM | buffer.COPY_DST : 0x0040 | 0x0008,
        render: texture ? texture.RENDER_ATTACHMENT : 0x0010,
    };
}

/**
 * Case-study WebGPU field for flood mapping. The photograph remains the LCP
 * and the canonical visual; this block ships `hidden` and is only revealed
 * once an adapter *and* device are acquired, so no visitor ever sees an
 * empty canvas box. Rendering pauses while off-screen or the tab is hidden.
 */
export async function initWebGpuFlood() {
    const canvas = document.querySelector('[data-webgpu-flood]');
    const root = canvas?.closest('[data-webgpu-flood-root]') ?? canvas;
    if (!canvas || !root) return;

    if (prefersReducedMotion || !navigator.gpu) return;

    try {
        const adapter = await navigator.gpu.requestAdapter();
        if (!adapter) return;
        const device = await adapter.requestDevice();
        const context = canvas.getContext('webgpu');
        if (!context) return;

        const usage = gpuUsage();
        const format = navigator.gpu.getPreferredCanvasFormat();

        device.addEventListener('uncapturederror', () => {
            root.hidden = true;
        });

        const module = device.createShaderModule({ code: WGSL });
        const pipeline = device.createRenderPipeline({
            layout: 'auto',
            vertex: { module, entryPoint: 'vs' },
            fragment: { module, entryPoint: 'fs', targets: [{ format }] },
            primitive: { topology: 'triangle-list' },
        });

        const uniformBuffer = device.createBuffer({
            size: 16,
            usage: usage.uniform,
        });
        const bindGroup = device.createBindGroup({
            layout: pipeline.getBindGroupLayout(0),
            entries: [{ binding: 0, resource: { buffer: uniformBuffer } }],
        });

        root.hidden = false;
        await new Promise((resolve) => requestAnimationFrame(resolve));

        let configuredW = 0;
        let configuredH = 0;

        const sizeCanvas = () => {
            const dpr = Math.min(window.devicePixelRatio || 1, 2);
            const cssW = canvas.clientWidth || canvas.parentElement?.clientWidth || 960;
            const cssH = canvas.clientHeight || Math.round(cssW * (9 / 16));
            const width = Math.max(1, Math.floor(cssW * dpr));
            const height = Math.max(1, Math.floor(cssH * dpr));
            if (width === configuredW && height === configuredH) {
                return { width, height };
            }
            canvas.width = width;
            canvas.height = height;
            context.configure({
                device,
                format,
                alphaMode: 'opaque',
                usage: usage.render,
            });
            configuredW = width;
            configuredH = height;
            return { width, height };
        };

        const start = performance.now();
        let raf = 0;
        let inView = true;

        const frame = (now) => {
            raf = 0;
            if (!inView || document.hidden) return;
            const { width, height } = sizeCanvas();
            const time = (now - start) / 1000;
            device.queue.writeBuffer(uniformBuffer, 0, new Float32Array([time, 0, width, height]));
            const encoder = device.createCommandEncoder();
            const pass = encoder.beginRenderPass({
                colorAttachments: [
                    {
                        view: context.getCurrentTexture().createView(),
                        loadOp: 'clear',
                        storeOp: 'store',
                        clearValue: { r: 0.1, g: 0.16, b: 0.2, a: 1 },
                    },
                ],
            });
            pass.setPipeline(pipeline);
            pass.setBindGroup(0, bindGroup);
            pass.draw(3);
            pass.end();
            device.queue.submit([encoder.finish()]);
            raf = requestAnimationFrame(frame);
        };

        const resume = () => {
            if (!raf && inView && !document.hidden) raf = requestAnimationFrame(frame);
        };

        new IntersectionObserver(
            ([entry]) => {
                inView = entry.isIntersecting;
                resume();
            },
            { rootMargin: '120px' }
        ).observe(canvas);

        new ResizeObserver(resume).observe(canvas);
        document.addEventListener('visibilitychange', resume);
        resume();
    } catch {
        root.hidden = true;
    }
}
