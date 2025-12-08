@if(session('sukses'))
<div x-data="{show:true}" x-show="show"
     x-init="setTimeout(() => show = false, 2000)"
     class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">
    {{ session('sukses') }}
</div>
@endif
