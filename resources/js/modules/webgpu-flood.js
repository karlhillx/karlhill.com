import { prefersReducedMotion } from '../lib/prefs.js';

const WGSL = /* wgsl */ `
struct Uniforms { time: f32, _pad: vec3f }
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
  return mix(mix(hash(i), hash(i + vec2f(1.0, 0.0)), u.x),
             mix(hash(i + vec2f(0.0, 1.0)), hash(i + vec2f(1.0, 1.0)), u.x), u.y);
}

@fragment
fn fs(@builtin(position) frag: vec4f) -> @location(0) vec4f {
  let uv = frag.xy / vec2f(960.0, 540.0);
  let n = noise(uv * 6.0 + vec2f(u.time * 0.08, u.time * 0.05));
  let flood = smoothstep(0.42, 0.58, n + uv.y * 0.18);
  let water = vec3f(0.12, 0.22, 0.28);
  let land = vec3f(0.06, 0.06, 0.07);
  let accent = vec3f(0.83, 0.57, 0.23);
  var color = mix(land, water, flood);
  color = mix(color, accent, flood * 0.18 * (1.0 - uv.y));
  return vec4f(color, 1.0);
}
`;

/**
 * Case-study WebGPU field for flood mapping. Photograph remains the LCP;
 * this canvas is progressive and hidden when WebGPU or motion is unavailable.
 */
export async function initWebGpuFlood() {
    const canvas = document.querySelector('[data-webgpu-flood]');
    const fallback = document.querySelector('[data-webgpu-fallback]');
    if (!canvas) return;

    const fail = () => {
        canvas.hidden = true;
        fallback?.removeAttribute('hidden');
    };

    if (prefersReducedMotion || !navigator.gpu) {
        fail();
        return;
    }

    try {
        const adapter = await navigator.gpu.requestAdapter();
        if (!adapter) {
            fail();
            return;
        }
        const device = await adapter.requestDevice();
        const context = canvas.getContext('webgpu');
        if (!context) {
            fail();
            return;
        }

        const format = navigator.gpu.getPreferredCanvasFormat();
        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        const width = Math.floor(canvas.clientWidth * dpr) || 960;
        const height = Math.floor(canvas.clientHeight * dpr) || 540;
        canvas.width = width;
        canvas.height = height;
        context.configure({ device, format, alphaMode: 'opaque' });

        const module = device.createShaderModule({ code: WGSL });
        const pipeline = device.createRenderPipeline({
            layout: 'auto',
            vertex: { module, entryPoint: 'vs' },
            fragment: { module, entryPoint: 'fs', targets: [{ format }] },
            primitive: { topology: 'triangle-list' },
        });

        const uniformBuffer = device.createBuffer({
            size: 16,
            usage: 0x0040 | 0x0008,
        });
        const bindGroup = device.createBindGroup({
            layout: pipeline.getBindGroupLayout(0),
            entries: [{ binding: 0, resource: { buffer: uniformBuffer } }],
        });

        const start = performance.now();
        const frame = (now) => {
            const time = (now - start) / 1000;
            device.queue.writeBuffer(uniformBuffer, 0, new Float32Array([time, 0, 0, 0]));
            const encoder = device.createCommandEncoder();
            const pass = encoder.beginRenderPass({
                colorAttachments: [
                    {
                        view: context.getCurrentTexture().createView(),
                        loadOp: 'clear',
                        storeOp: 'store',
                        clearValue: { r: 0.04, g: 0.04, b: 0.04, a: 1 },
                    },
                ],
            });
            pass.setPipeline(pipeline);
            pass.setBindGroup(0, bindGroup);
            pass.draw(3);
            pass.end();
            device.queue.submit([encoder.finish()]);
            canvas._raf = requestAnimationFrame(frame);
        };
        canvas._raf = requestAnimationFrame(frame);
    } catch {
        fail();
    }
}
