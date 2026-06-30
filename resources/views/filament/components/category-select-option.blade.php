<span style="display: inline-flex; align-items: center; gap: 0.5rem;">
    @if($imageUrl)
        <span style="display: inline-flex; align-items: center; justify-content: center; width: 1.5rem; height: 1.5rem; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 0.375rem; flex-shrink: 0;">
            <img src="{{ $imageUrl }}" alt="" style="width: 1rem; height: 1rem; object-fit: contain;" />
        </span>
    @else
        <span style="width: 1.25rem; height: 1.25rem; display: inline-block; background: #e5e7eb; border-radius: 0.25rem; flex-shrink: 0;"></span>
    @endif
    <span>{{ $name }}</span>
</span>
