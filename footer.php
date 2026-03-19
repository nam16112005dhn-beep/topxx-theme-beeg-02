    <div id="totop"></div>
    <div class="menu_holder">
        <span class="menu_close"></span>
        <div class="first_part_sort">
            <form action="/new/" class="sidebar_sort" method="GET" id="filter_videos">
                <div class="filter-panel-header">
                    <span class="filter-panel-title">Bộ lọc</span>
                    <button type="button" class="filter-panel-close">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor"><path d="M13.3 0.7a1 1 0 0 0-1.4 0L7 5.6 2.1 0.7a1 1 0 0 0-1.4 1.4L5.6 7 0.7 11.9a1 1 0 1 0 1.4 1.4L7 8.4l4.9 4.9a1 1 0 0 0 1.4-1.4L8.4 7l4.9-4.9a1 1 0 0 0 0-1.4z"/></svg>
                    </button>
                </div>

                <div class="filter-section">
                    <p class="filter-section-title">Thời lượng</p>
                    <label class="filter-radio">
                        <input type="radio" name="dsort" value="">
                        <span class="filter-radio-circle"></span>
                        <span class="filter-radio-label">Tất cả</span>
                    </label>
                    <label class="filter-radio">
                        <input type="radio" name="dsort" value="long">
                        <span class="filter-radio-circle"></span>
                        <span class="filter-radio-label">Dài</span>
                    </label>
                </div>

                <div class="filter-divider"></div>

                <div class="filter-section">
                    <p class="filter-section-title">Sắp xếp</p>
                    <label class="filter-radio">
                        <input type="radio" name="filter" value="new">
                        <span class="filter-radio-circle"></span>
                        <span class="filter-radio-label">Mới nhất</span>
                    </label>
                    <label class="filter-radio">
                        <input type="radio" name="filter" value="best">
                        <span class="filter-radio-circle"></span>
                        <span class="filter-radio-label">Hay nhất</span>
                    </label>
                    <label class="filter-radio">
                        <input type="radio" name="filter" value="views">
                        <span class="filter-radio-circle"></span>
                        <span class="filter-radio-label">Xem nhiều nhất</span>
                    </label>
                </div>

                <div class="filter-divider"></div>

                <div class="filter-panel-actions">
                    <button type="button" class="filter-btn-reset reset_my_videos">Đặt lại</button>
                    <button type="button" class="filter-btn-apply show_my_videos">Áp dụng</button>
                </div>
            </form>
                <div class="root_menu  ">
                    <ul class="navigation_menu">
                        <?php
                        $menu_items = op_get_menu_array('primary-menu');
                        foreach ($menu_items as $key => $item) : ?>
                            <li><a href="<?= $item['url'] ?>"><?= $item['title'] ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="container">
            <div class="container">
                <div>
                    <div class="headline">
                        <h2>Keywords</h2>
                    </div>

                    <p class="text-categories footer-keywords-list">
                        <?php $keywords = get_terms(array('taxonomy' => 'ophim_tags', 'hide_empty' => false, 'number' => 16, 'orderby' => 'count', 'order' => 'DESC'));?>
                        <?php foreach($keywords as $keyword) { ?>
                            <a href="<?php echo get_term_link($keyword->term_id); ?>" class="footer-keyword-item" title="<?php echo esc_attr($keyword->name); ?>"><?php echo esc_html($keyword->name); ?></a>
                        <?php } ?>
                    </p>
                </div>
                            <br>
                <div>
                    <div class="headline">
                        <h2>Categories</h2>
                    </div>

                    <p class="text-categories">
                        <?php $categories = get_terms(array('taxonomy' => 'ophim_genres', 'hide_empty' => false, 'number' => 16, 'orderby' => 'count', 'order' => 'DESC'));?>
                        <?php foreach($categories as $category) { ?>
                            <a href="<?php echo get_term_link($category->term_id); ?>"><?php echo $category->name; ?></a>
                        <?php } ?>
                    </p>
                </div>
            </div>
        </div>

        <?php
        if ( is_active_sidebar('widget-footer') ) {
            dynamic_sidebar( 'widget-footer' );
        } else {
            _e('This is widget footer. Go to Appearance -> Widgets to add some widgets.', 'ophim');
        }
        ?>
        <?php wp_footer(); ?>

        <script src="<?= get_template_directory_uri() ?>/assets/js/jquery.js"></script>

        <script type="text/javascript"> 
function shuffleArray(array) {
    var currentIndex = array.length, temporaryValue, randomIndex;

    // While there remain elements to shuffle...
    while (currentIndex !== 0) {
        // Pick a remaining element...
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex--;

        // And swap it with the current element.
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
    }

    return array;
}

var FILTER_STORAGE_KEY = 'ophim_video_filter';

function getFilterStateFromUrl() {
    var path = window.location.pathname || '';
    var params = new URLSearchParams(window.location.search || '');
    var filter = 'new';
    var dsort = params.get('dsort') || '';
    if (path.indexOf('/best/') !== -1) {
        filter = params.get('order') === 'views' ? 'views' : 'best';
    } else if (path.indexOf('/new/') !== -1) {
        filter = 'new';
    }
    return { filter: filter, dsort: dsort };
}

function applyFilterStateToForm(state) {
    if (!state) return;
    $("input[name=filter][value='" + state.filter + "']").prop('checked', true);
    $("input[name=dsort][value='" + (state.dsort || '') + "']").prop('checked', true);
}

function loadSavedFilter() {
    var state = { filter: 'new', dsort: '' };
    try {
        var path = window.location.pathname || '';
        if (path.indexOf('/new/') !== -1 || path.indexOf('/best/') !== -1) {
            state = getFilterStateFromUrl();
        } else {
            var raw = localStorage.getItem(FILTER_STORAGE_KEY);
            if (raw) {
                var parsed = JSON.parse(raw);
                if (parsed && (parsed.filter || parsed.dsort !== undefined)) state = { filter: parsed.filter || 'new', dsort: parsed.dsort || '' };
            }
        }
        localStorage.setItem(FILTER_STORAGE_KEY, JSON.stringify(state));
        applyFilterStateToForm(state);
    } catch (e) {
        applyFilterStateToForm({ filter: 'new', dsort: '' });
    }
}

function initURL() {
    loadSavedFilter();
    $(".menu_holder form").submit(function() { return false; });
    $(".menu_holder .show_my_videos").click(function() {
        var filter = $("input[name=filter]:checked").val();
        var dsort = $("input[name=dsort]:checked").val();
        var url = '/new/';
        if (filter === 'best') url = '/best/';
        else if (filter === 'views') url = '/best/?order=views';
        if (dsort && url.indexOf('?') === -1) { url += '?dsort=' + dsort; }
        else if (dsort) { url += '&dsort=' + dsort; }
        var state = { filter: filter || 'new', dsort: dsort || '' };
        try { localStorage.setItem(FILTER_STORAGE_KEY, JSON.stringify(state)); } catch (e) {}
        window.location = url;
    });
    $(".menu_holder .reset_my_videos").click(function() {
        $("input[name=filter][value=new]").prop('checked', true);
        $("input[name=dsort][value='']").prop('checked', true);
        try { localStorage.setItem(FILTER_STORAGE_KEY, JSON.stringify({ filter: 'new', dsort: '' })); } catch (e) {}
        window.location = '/new/';
    });
}
var domain = document.domain,
    my_url = "//" + domain,
    current_site = my_url,
    id_input = "#wordinput",
    id_list = "#worddiv",
    suggest_count = 0,
    suggest_selected = 0;

function e(e) {
    $(id_list + " div").eq(suggest_selected - 1).removeClass("active"), 1 == e && suggest_count > suggest_selected ? suggest_selected++ : -1 == e && suggest_selected > 0 && suggest_selected--, suggest_selected > 0 && $(id_list + " div").eq(suggest_selected - 1).addClass("active")
}
$(".show_menu").click(function() {
    var $mh = $(".menu_holder");
    if ($mh.hasClass("open") && $mh.hasClass("mode-menu")) {
        $mh.removeClass("open mode-menu mode-filter");
    } else {
        $mh.removeClass("mode-filter").addClass("mode-menu open");
    }
    return !1;
});
$(".menu_close, .filter-panel-close").click(function() {
    $(".menu_holder").removeClass("open mode-menu mode-filter");
    return !1;
});
$(".a_show_filter").click(function() {
    var $mh = $(".menu_holder");
    if ($mh.hasClass("open") && $mh.hasClass("mode-filter")) {
        $mh.removeClass("open mode-menu mode-filter");
    } else {
        $mh.removeClass("mode-menu").addClass("mode-filter open");
    }
    return !1;
});
$("#totop").click(function() {
    $("html, body").animate({
        scrollTop: 0
    }, 400)
});
$(window).scroll(function() {
    $(this).scrollTop() > 0 ? $("#totop").show(300) : $("#totop").hide(300)
});
initURL();

function getRandom(arr, n) {
    var result = new Array(n),
        len = arr.length,
        taken = new Array(len);
    if (n > len) throw new RangeError("getRandom: more elements taken than available");
    while (n--) {
        var x = Math.floor(Math.random() * len);
        result[n] = arr[x in taken ? taken[x] : x];
        taken[x] = --len in taken ? taken[len] : len;
    }
    return result;
}

function replaceAll(str, find, replace) {
    return str.replace(new RegExp(escapeRegExp(find), 'g'), replace);
}

function site_pre_lang(url)
{
     
    return url;
}


function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
}


function titleCase(string) {
    return string;
}
 


 
  

</script>
    </body>
</html>