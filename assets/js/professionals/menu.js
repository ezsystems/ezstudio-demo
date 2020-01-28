window.onload = function() {
    let menuItems = document.querySelectorAll('.menu-list-item__toggler');

    menuItems.forEach(menuItem => {
        menuItem.addEventListener('click', event => {
            let menuNode = event.target.closest('.menu-list-item--has-sub-items');
            let expandedNodes = document.querySelectorAll('.menu-list-item--has-sub-items.expand');

            if (expandedNodes.length === 0) {
                expandNode(menuNode);
            }
            expandedNodes.forEach(expandedItem => {
                if (typeof menuNode.dataset.treeRootLocationId === "undefined") {
                    collapseNode(expandedItem);

                    if (expandedItem.dataset.locationId !== menuNode.dataset.locationId
                        && expandedItem.dataset.treeRootLocationId !== menuNode.dataset.locationId) {
                        expandNode(menuNode);
                    }
                }
                else {
                    if (menuNode.dataset.treeRootLocationId !== expandedItem.dataset.locationId) {
                        collapseNode(expandedItem);
                    } else {
                        expandNode(menuNode);
                    }
                }
            });
        });
    });
};

function expandNode(menuItem) {
    if (menuItem.classList.contains('collapse')) {
        menuItem.classList.remove('collapse');
        menuItem.classList.add('expand');
    }
}

function collapseNode(menuItem) {
    if (menuItem.classList.contains('expand')) {
        menuItem.classList.remove('expand');
        menuItem.classList.add('collapse');
    }
}
