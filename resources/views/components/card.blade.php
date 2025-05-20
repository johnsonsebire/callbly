@props(['title' => '', 'icon' => '', 'headerClass' => 'bg-light'])

<div {{ $attributes->merge(['class' => 'card shadow-sm']) }}>
    @if($title || $icon || isset($header))
        <div class="card-header {{ $headerClass }}">
            @if(isset($header))
                {{ $header }}
            @else
                <h5 class="card-title mb-0">
                    @if($icon)
                        <i class="{{ $icon }} me-2"></i>
                    @endif
                    {{ $title }}
                </h5>
            @endif
        </div>
    @endif
    
    <div class="card-body">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>