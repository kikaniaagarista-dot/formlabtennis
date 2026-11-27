<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Class - FORMLAB Tennis</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            padding: 40px 20px;
            background: #101010;
            color: white;
        }

        .booking-container {
            max-width: 950px;
            margin: auto;
            padding: 35px;
            background: #1b1b1b;
            border-radius: 12px;
        }

        h1, h2 {
            color: #b6ff00;
        }

        .class-info, .package-info {
            padding: 18px;
            background: #292929;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .table-wrapper {
            overflow-x: auto;
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #444;
            text-align: left;
        }

        th {
            color: #b6ff00;
            background: #292929;
        }

        input[type="checkbox"] {
            accent-color: #b6ff00;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #444;
            border-radius: 6px;
            background: #292929;
            color: white;
        }

        button {
            width: 100%;
            padding: 14px;
            margin-top: 25px;
            border: none;
            border-radius: 6px;
            background: #b6ff00;
            color: black;
            font-weight: bold;
            cursor: pointer;
        }

        .error {
            color: #ff7777;
            font-size: 13px;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            color: #b6ff00;
            text-decoration: none;
        }

        .empty {
            padding: 20px;
            background: #292929;
            border-radius: 8px;
            color: #ccc;
        }

        .selection-info {
            color: #b6ff00;
            margin-top: 12px;
        }
    </style>
</head>

<body>

<div class="booking-container">

    <h1>Book Your Class</h1>
    <p>Select your training schedule below.</p>

    <div class="class-info">
        <h2>{{ $class->name }}</h2>
        <p>{{ $class->description }}</p>
    </div>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('member.booking.store', $class->id) }}" method="POST">
        @csrf

        <div class="package-info">
            <h2>Choose Payment Package</h2>

            @php
                $packagePrices = $class->packagePrices();
            @endphp

            <select name="payment_method" id="payment_method" required>
                <option value="">-- Select Package --</option>
                <option value="per_meeting" {{ old('payment_method') == 'per_meeting' ? 'selected' : '' }}>
                    Pay Per Meeting - Rp{{ number_format($packagePrices['per_meeting'], 0, ',', '.') }} (1 Schedule)
                </option>
                <option value="monthly_4" {{ old('payment_method') == 'monthly_4' ? 'selected' : '' }}>
                    Monthly Membership - 4 Sessions - Rp{{ number_format($packagePrices['monthly_4'], 0, ',', '.') }} (save 6.25%)
                </option>
                <option value="monthly_6" {{ old('payment_method') == 'monthly_6' ? 'selected' : '' }}>
                    Monthly Membership - 6 Sessions - Rp{{ number_format($packagePrices['monthly_6'], 0, ',', '.') }} (save 10%)
                </option>
            </select>
        </div>



        <div class="package-info">
            <h2>Number of Participants</h2>

            <label for="participants">How many people are booking?</label>

            <input
                type="number"
                name="participants"
                id="participants"
                value="{{ old('participants', 1) }}"
                min="1"
                max="{{ $class->capacity }}"
                required
                style="
                    width: 100%;
                    padding: 12px;
                    margin-top: 10px;
                    border: 1px solid #444;
                    border-radius: 6px;
                    background: #292929;
                    color: white;
                    font-size: 16px;
                "
            >

            <p style="color:#b6ff00; margin-top:12px; font-size:13px;">
                Adjust the number of participants using the arrows.
            </p>
        </div>



        <h2>Available Training Schedules</h2>
        <p>Select the schedule according to your chosen package.</p>

        @if ($schedules->count() > 0)
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Location</th>
                            <th>Available Slots</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($schedules as $schedule)
                            <tr>
                                <td>
                                    @php
                                        $booked = $schedule->booked_participants ?? 0;
                                        $remaining = max(0, $schedule->capacity - $booked);
                                    @endphp

                                    <input
                                        type="checkbox"
                                        name="schedule_ids[]"
                                        value="{{ $schedule->id }}"
                                        data-remaining="{{ $remaining }}"
                                        {{ in_array($schedule->id, old('schedule_ids', [])) ? 'checked' : '' }}
                                        {{ $remaining < (int) old('participants', 1) ? 'disabled' : '' }}
                                    >
                                </td>

                                <td>{{ $schedule->schedule_date->format('d-m-Y') }}</td>

                                <td>
                                    {{ $schedule->schedule_date->locale('id')->translatedFormat('l') }}
                                </td>

                                <td>
                                    {{ substr($schedule->start_time, 0, 5) }}
                                    -
                                    {{ substr($schedule->end_time, 0, 5) }} WITA
                                </td>

                                <td>
                                    {{ $schedule->location->name ?? '-' }}

                                    @if ($schedule->location && $schedule->location->map_link)
                                        <br>
                                        <a href="{{ $schedule->location->map_link }}"
                                           target="_blank"
                                           style="color:#b6ff00;">
                                            Open Map
                                        </a>
                                    @endif
                                </td>

                                <td>
                                    @php
                                        $booked = $schedule->booked_participants ?? 0;
                                        $remaining = max(0, $schedule->capacity - $booked);
                                    @endphp

                                    @if ($remaining > 0)
                                        <span style="color:#b6ff00;">
                                            {{ $remaining }} / {{ $schedule->capacity }} Slots
                                        </span>
                                    @else
                                        <span style="color:#ff7777;">
                                            Full
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p class="selection-info" id="selection-info">
                Selected: 0 schedules
            </p>

            <div class="package-info" style="margin-top:20px;">
                <h2>Payment Summary</h2>

                <p id="price-per-person">Price per participant: -</p>
                <p id="total-participants">Total participants: 1</p>

                <h2 id="total-price" style="margin-top:15px;">
                    Total: Rp0
                </h2>
            </div>

        @else
            <div class="empty">
                No training schedules are currently available.
            </div>
        @endif

        <button type="submit">Continue to Payment</button>
    </form>

    <a href="{{ route('member.classes') }}" class="back">
        ← Back to Classes
    </a>

</div>


<script>
    const packageSelect = document.getElementById('payment_method');
    const participantsSelect = document.getElementById('participants');
    const checkboxes = document.querySelectorAll('input[name="schedule_ids[]"]');
    const selectionInfo = document.getElementById('selection-info');

    function updateSelection() {
        const participants = Number(participantsSelect.value);

        let required = 1;

        if (packageSelect.value === 'monthly_4') {
            required = 4;
        } else if (packageSelect.value === 'monthly_6') {
            required = 6;
        }

        // Lepaskan jadwal yang kapasitasnya tidak mencukupi.
        checkboxes.forEach(checkbox => {
            const remaining = Number(checkbox.dataset.remaining);

            if (checkbox.checked && remaining < participants) {
                checkbox.checked = false;
            }
        });

        const selected = document.querySelectorAll(
            'input[name="schedule_ids[]"]:checked'
        ).length;

        // Atur jadwal yang masih bisa dipilih.
        checkboxes.forEach(checkbox => {
            const remaining = Number(checkbox.dataset.remaining);

            checkbox.disabled =
                !checkbox.checked &&
                (remaining < participants || selected >= required);
        });

        selectionInfo.textContent =
            `Selected: ${selected} / ${required} schedules | ${participants} participants`;

        // Harga diambil dari server (harga kelas yang diatur admin).
        const prices = @json($packagePrices);

            const price = prices[packageSelect.value] || 0;
            const totalPrice = price * participants;

            document.getElementById('price-per-person').textContent =
                'Price per participant: Rp' + price.toLocaleString('id-ID');

            document.getElementById('total-participants').textContent =
                'Total participants: ' + participants;

            document.getElementById('total-price').textContent =
                'Total: Rp' + totalPrice.toLocaleString('id-ID');
    }

    packageSelect.addEventListener('change', function () {
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });

        updateSelection();
    });

    participantsSelect.addEventListener('change', updateSelection);

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelection);
    });

    updateSelection();
</script>


</body>
</html>