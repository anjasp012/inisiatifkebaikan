@props(['prayer'])

<div class="card prayer-card">
    <div class="card-body">
        <div class="prayer-card-header">
            <div class="prayer-card-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <div>
                <div class="prayer-card-name">{{ $prayer->donor_name }}</div>
                @if ($prayer->campaign)
                    <div class="prayer-card-campaign">{{ $prayer->campaign->title }}</div>
                @endif
            </div>
        </div>

        <p class="prayer-card-message">"{{ $prayer->message }}"</p>

        <div class="prayer-card-footer">
            <button class="prayer-card-amin" x-data="{
                count: {{ $prayer->amin_count }},
                done: localStorage.getItem('amin_{{ $prayer->id }}') === '1'
            }"
                x-on:click="
                    if(!done) {
                        count++;
                        done = true;
                        localStorage.setItem('amin_{{ $prayer->id }}', '1');
                        fetch('/api/prayer/{{ $prayer->id }}/amin', {
                            method: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                        })
                    }
                "
                :class="{ 'is-done': done }">
                🤲 Amin (<span x-text="count"></span>)
            </button>
            <span class="prayer-card-time">{{ $prayer->created_at->diffForHumans() }}</span>
        </div>
    </div>
</div>
