(function ($) {
    $(document).ready(function() {
    
        // Display our list of countries when the parent is clicked
        $('.view-region-list h3').click(function() {
            $(this).siblings('ul').slideToggle(250);
            $(this).toggleClass('open');
            
            // Hide any lists that may be open
            $(this).parents('.item-list').siblings().find('ul').slideUp(250);
            $(this).parents('.item-list').siblings().find('h3').removeClass('open');
        });
        
        // Change our menu dropdown behaviour when on mobile
        $('li.expanded ul.menu').click(function() {
            $(this).closest('.expanded').toggleClass('toggleDropdown');
        });
        
        // Toggle menu on mobile
        $('div.mobile-menu span.button').click(function() {
            $(this).closest('.mobile-menu').toggleClass('toggled');
            return false;
        });
        
        // Click to show search menu, but allow fallback for users without jQuery
        $('div#utility div#panel-search.panel').mouseenter(function() {
            $(this).children('div.content').addClass('search-active');
            $(this).css('cursor', 'pointer');
        });
        $('div#utility div#panel-search.panel .title').click(function() {
            $(this).siblings('div.content').toggleClass('show-search');
            $(this).toggleClass('highlight');
        });
        $('div#utility div#panel-search.panel').mouseleave(function() {
            $(this).children('div.content').removeClass('search-active');
        });
        
        // Open social media links in new window
        $('.social-link').attr('target', '_blank');
        
        // Here begineth our location tab hack...
        if($('body').hasClass('page-node-4649') || $('body').hasClass('page-node-4923') || $('body').hasClass('page-node-4922')) {
            /*
             * First remove the active quicktab set by default
            */
            $('#quicktabs-offices ul li').each(function() {
                $(this).removeClass('active');
            });
            /*
             * Check our location and append class to li accordingly
            */
            if($('body').hasClass('loc-us')) {
                $('#quicktabs-tab-offices-1').closest('li').addClass('active');
                $('#quicktabs-tabpage-offices-1').removeClass('quicktabs-hide');
                $('#quicktabs-tabpage-offices-1').siblings().addClass('quicktabs-hide');
            } else if($('body').hasClass('loc-ch')) {
                $('#quicktabs-tab-offices-2').closest('li').addClass('active');
                $('#quicktabs-tabpage-offices-2').removeClass('quicktabs-hide');
                $('#quicktabs-tabpage-offices-2').siblings().addClass('quicktabs-hide');
            } else {
                $('#quicktabs-tab-offices-0').closest('li').addClass('active');
                $('#quicktabs-tabpage-offices-0').removeClass('quicktabs-hide');
                $('#quicktabs-tabpage-offices-0').siblings().addClass('quicktabs-hide');
            }
        }
    });
})(jQuery);