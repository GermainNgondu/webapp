@props(['items'])
<div class="pt-6">
   {{ $items->links(data: ['scrollTo' => false]) }}
</div>