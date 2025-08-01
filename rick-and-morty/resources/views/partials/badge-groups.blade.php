<div class="d-flex flex-wrap justify-content-center gap-4 my-4">
    @foreach ($badges as $badge)
        @php
            $ownedMembers = collect($badge['members'])->where('owned', true);
        @endphp
        @if ($ownedMembers->isNotEmpty())
            <div class="badge-group mb-4 text-center">
                <h4 class="mb-2">
                    {{ $badge['title'] }}
                    @if($badge['completed'])
                        <i class="bi bi-check-lg text-success"></i>
                    @endif
                </h4>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    @foreach ($badge['members'] as $member)
                        <div class="position-relative">
                            <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}"
                                class="badge-head {{ $member['owned'] ? '' : 'locked' }}"
                                style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;"
                                title="{{ $member['name'] }}">
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</div>

<style>
    .badge-group {
        padding: 16px;
        min-width: 200px;
        background-color: #f8f9fa;
        border-radius: 12px;
    }

    .badge-group h4 {
        font-size: 18px;
        margin-bottom: 8px;
    }

    .badge-head {
        border: 2px solid #198754;
        box-shadow: 0 0 8px rgba(25, 135, 84, 0.5);
        transition: filter 0.3s, opacity 0.3s;
        cursor: default;
    }

    .badge-head.locked {
        filter: grayscale(100%) brightness(0.5);
        box-shadow: none;
        opacity: 0.5;
    }

    .badge-head:hover {
        transform: scale(1.1);
        z-index: 10;
    }
</style>