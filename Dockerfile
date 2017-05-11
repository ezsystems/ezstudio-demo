FROM ezsystems/php:7.1-v1 as builder

# This is prod image (for dev use just mount your application as host volume into php image we extend here)
ENV SYMFONY_ENV=prod

# Copy in project files into work dir
COPY . /var/www

# Check for ignored folders to avoid layer issues, ref: https://github.com/docker/docker/issues/783
RUN if [ -d .git ]; then echo "ERROR: .dockerignore folders detected, exiting" && exit 1; fi

# Install and prepare install
RUN mkdir -p web/var
# For now, only run composer in order to generate parameters.yml
RUN composer run-script build --no-interaction
RUN composer dump-autoload --optimize

# Next, remove everything we don't want to be copied to next build stage
# Clear cache again so env variables are taken into account on startup
RUN rm -Rf app/logs/* app/cache/*/*
RUN rm -rf web/bundles web/css web/fonts web/js web/var


FROM ezsystems/php:7.1-v1

# This is prod image (for dev use just mount your application as host volume into php image we extend here)
ENV SYMFONY_ENV=prod

COPY --from=0 /var/www /var/www

# Fix permissions for www-data
RUN chown -R www-data:www-data app/cache app/logs \
    && find app/cache app/logs -type d -print0 | xargs -0 chmod -R 775 \
    && find app/cache app/logs -type f -print0 | xargs -0 chmod -R 664
