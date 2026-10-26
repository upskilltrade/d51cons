{{--
    Published services, or the brand's four service lines while the owner is
    still confirming them in admin (links then go to the contact form).
--}}
@php
    $fallbackImages = [
        'images/concepts/service-custom-home.webp',
        'images/concepts/service-pool-villa.webp',
        'images/concepts/commercial-building.webp',
        'images/concepts/service-renovation.webp',
    ];
    $items = $services->isNotEmpty() ? $services : collect(__('site.default_services'));
@endphp
<div class="service-grid reveal">
    @foreach ($items as $service)
        <x-service-card
            :service="$service"
            :number="$loop->iteration"
            :image="$fallbackImages[$loop->index % 4]"
            :href="$service instanceof \App\Models\Service ? route('services.show', $service) : route('contact')" />
    @endforeach
</div>
