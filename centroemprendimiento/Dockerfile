FROM node:10.16.1-alpine AS node
WORKDIR /centroemprendimiento
COPY package.json .
COPY package-lock.json .

RUN npm i
COPY . .
#RUN npm run build --prod

FROM nginx:1.15.8-alpine

COPY --from=node /centroemprendimiento/dist/ce /usr/share/nginx/html 


