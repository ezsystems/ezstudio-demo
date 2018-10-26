jQuery(() => {
    let $ = jQuery;

    $('body').on('postUpdateBlocksPreview', (e) => {
        const blocksMap = e.detail.blocksMap;

        for (let key in blocksMap) {
            let block = blocksMap[key],
                tries = 0,
                maxTries = 20;

            if (block.config.type !== 'places') {
                continue;
            }

            const mapContainer = $('.landing-page__block[data-ez-block-id="' + key + '"]').find('.map-container'),
                mapContainerId = mapContainer.attr('id');

            // waiting for new map container Id - rendered by preview
            let stateCheck = setInterval(() => {
                var newMapContainer = $('.landing-page__block[data-ez-block-id="' + key + '"]').find('.map-container');

                if (tries > maxTries) {
                    clearInterval(stateCheck);
                    return ;
                }

                if (newMapContainer.attr('id') === mapContainerId) {
                    tries = tries + 1;
                    return ;
                }

                clearInterval(stateCheck);
                window.mapLoader('#' + newMapContainer.attr('id'));
            }, 500);
        }
    });
});
