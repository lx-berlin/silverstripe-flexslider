<div class="flexslider flexslider_{$ID} $extraClass" <% if $cssWidth %>style="width: {$cssWidth}px"<% end_if %>>
  <ul class="slides">
    <% loop getSlides %>
        <% if $Picture %>
        <li>
            <% if $getSlideTarget %><a href="$getSlideTarget" target="_blank"><% end_if %>
                $flexCroppedImage($Top.imageWidth, $Top.imageHeight)
            <% if $getSlideTarget %></a><% end_if %>
            <% if $SlideTitle || $SlideDescription %>
                <p class="flex-caption">
                    <% if $SlideTitle %><span class="flex-caption-inner heading">$SlideTitle</span><% end_if %>
                    <% if $SlideDescription %><span class="flex-caption-inner description">$SlideDescription</span><% end_if %>
                </p>
            <% end_if %>
        </li>
        <% end_if %>
    <% end_loop %>
  </ul>
</div>
