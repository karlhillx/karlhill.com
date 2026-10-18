FROM python:3.12-slim

COPY --from=ghcr.io/astral-sh/uv:latest /uv /usr/local/bin/uv

WORKDIR /app
COPY . .
RUN uv sync --locked --no-dev

EXPOSE 8000
CMD ["uv", "run", "uvicorn", "linkpress_qr.app:app", "--host", "0.0.0.0", "--port", "8000", "--proxy-headers"]
