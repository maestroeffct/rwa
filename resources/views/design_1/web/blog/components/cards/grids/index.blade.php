@php
    $cardName = getThemeContentCardStyle("blog_post");
@endphp

@push('styles_top')
    @if(empty($withoutStyles))
        <link rel="stylesheet" href="{{ getDesign1StylePath("post_cards/{$cardName}") }}">
    @endif
@endpush

@foreach($posts as $post)
    <div class="{{ !empty($gridCardClassName) ? $gridCardClassName : '' }}">
        @include("design_1.web.blog.components.cards.grids.{$cardName}", ['post' => $post])
    </div>
@endforeach
