Scrapbook wheel creator
-----------------------

This project allows to generate a wheel to be used in a scrapbook, using a video as a source. It was made in a kind of quick-and-dirty way, with ffmpeg many more frames than needed and the PHP script filtering them out, but it gave me the output that I wanted for my use case.

Usage :
```bash
docker build . -t bperel/scrapbook-wheel && \
docker run --rm -it -v $(pwd):/home bperel/scrapbook-wheel my-video.mp4
```

The resulting wheel will be created under the name `wheel.jpg` in the base folder of the project.
