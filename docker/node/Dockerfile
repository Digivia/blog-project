FROM arm64v8/node:15-alpine

RUN mkdir -m 777 /.npm
RUN mkdir -m 777 /.yarn
RUN touch /.yarnrc && chmod 777 /.yarnrc
CMD ["yarn run dev"]