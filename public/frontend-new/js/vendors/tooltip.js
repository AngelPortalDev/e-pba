$(document).ready(function() {
    // Function to initialize tooltips with proper content
    function initializeTooltips() {
        tippy(".bookmark, .addwishlist, .addwishlistBrowse", {
            content: tooltips.add,
            theme: "light",
            animation: "scale",
            onShow(instance) {
                const icon = instance.reference.querySelector('.heart-icon');
                if (icon.classList.contains('bi-heart-fill')) {
                    instance.setContent(tooltips.remove);
                } else {
                    instance.setContent(tooltips.add);
                }
            }
        });

        tippy(".removeBookmark", {
            content: tooltips.remove,
            animation: "scale",
        });
    }

    initializeTooltips();


    $(document).on('click', '.heart-icon', function() {
        $(this).toggleClass('bi-heart bi-heart-fill');

        const icon = $(this);
        const tooltipInstance = tippy(icon[0]);

        if (tooltipInstance) {
            tooltipInstance.setContent(icon.hasClass('bi-heart-fill') ? "Remove from Wishlist" : "Add to Wishlist");
        }
    });
});
