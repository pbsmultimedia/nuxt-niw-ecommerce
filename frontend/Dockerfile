FROM node:20-alpine

WORKDIR /app

RUN apk add --no-cache git

COPY . .

EXPOSE 8080

# Tell Nuxt dev server to use port 8080
CMD ["sh", "-c", "npm install && npm run dev -- --host 0.0.0.0 --port 8080"]