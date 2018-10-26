(function (global, doc) {
    const mapLoader = (mapContainerId) => {
        const mapLocation = doc.querySelector(mapContainerId),
            map = global.L.map(mapLocation, {
                zoom: 1.5,
                zoomControl: false,
                scrollWheelZoom: false,
                dragging: false,
                tap: false,
                center: [40, 0],
                view: [40, 0]
            });
        map.doubleClickZoom.disable();

        const markers = doc.querySelector(mapContainerId + '_map_data').childNodes;
        markers.forEach(marker => {
            if (marker.className === 'map_place') {
                global.L.marker([
                    marker.getAttribute('data-lat'),
                    marker.getAttribute('data-lng')
                ], {
                    icon: new window.L.Icon.Default({
                        imagePath: '/bundles/ezplatformadminuiassets/vendors/leaflet/dist/images/'
                    }),
                    title: marker.getAttribute('data-name')
                }).addTo(map);
            }
        });

        global.L.tileLayer('/assets/images/map_tile/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> &copy; <a href="http://cartodb.com/attributions" target="_blank">CartoDB</a>',
            maxZoom: 19,
            noWrap: true
        }).addTo(map);
    };

    global.mapLoader = mapLoader;
})(window, document);
