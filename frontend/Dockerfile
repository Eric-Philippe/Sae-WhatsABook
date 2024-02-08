# Step 1: Build the app in node container
FROM node:12.7-alpine AS build
WORKDIR /usr/src/app
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build

# Step 2: Serve app with nginx server
FROM nginx:1.17.1-alpine
COPY --from=build /usr/src/app/dist/my-app /usr/share/nginx/html