@if ($metaPixel->isEnabled())
    @unless (empty($eventLayer) && empty($customEventLayer))
        <script>
            (function() {
                function fireMetaPixelEvents() {
                    if (typeof fbq === 'undefined') return;

                    {{-- Standard Events --}}
                    @foreach ($eventLayer ?? [] as $eventName => $metaPixel)
                        @if (empty($metaPixel['event_id']) && empty($metaPixel['data']))
                            fbq('track', '{{ $eventName }}');
                        @elseif (empty($metaPixel['event_id']))
                            fbq('track', '{{ $eventName }}', {{ Js::from($metaPixel['data']) }});
                        @else
                            fbq(
                                'track',
                                '{{ $eventName }}',
                                {{ Js::from($metaPixel['data']) }}, {
                                    eventID: '{{ $metaPixel['event_id'] }}'
                                }
                            );
                        @endif
                    @endforeach

                    {{-- Custom Events --}}
                    @foreach ($customEventLayer ?? [] as $customEventName => $metaPixel)
                        @if (empty($metaPixel['event_id']) && empty($metaPixel['data']))
                            fbq('trackCustom', '{{ $customEventName }}');
                        @elseif (empty($metaPixel['event_id']))
                            fbq('trackCustom', '{{ $customEventName }}', {{ Js::from($metaPixel['data']) }});
                        @else
                            fbq(
                                'trackCustom',
                                '{{ $customEventName }}',
                                {{ Js::from($metaPixel['data']) }}, {
                                    eventID: '{{ $metaPixel['event_id'] }}'
                                }
                            );
                        @endif
                    @endforeach
                }

                // 1️⃣ initial page load
                fireMetaPixelEvents();

                // 2️⃣ Livewire SPA navigation
                document.addEventListener('livewire:navigated', fireMetaPixelEvents);
            })();
        </script>
    @endunless
@endif
