@props(['defaultTab' => ''])

@php
    $now = \Carbon\Carbon::now();
    $daysInMonth = $now->daysInMonth;
    $firstDayOfWeek = $now->copy()->startOfMonth()->dayOfWeek; // 0 (Sun) - 6 (Sat)
    $todayDate = $now->day;
    $monthName = $now->translatedFormat('F Y');

    $isSeniTari = strpos(strtolower($defaultTab), 'tari') !== false;
    $timeRange = $isSeniTari ? '10:00 - 13:00' : '13:00 - 15:00';
    $programName = $defaultTab ?: 'Kelas Sanggar';
@endphp

<div class="main-schedule-wrapper">
    <!-- Area Kalender (Kiri) -->
    <div class="calendar-section glass-panel">
        <div class="calendar-header-top d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0" style="color: var(--sanggar-dark, #2D241E);">{{ $monthName }}</h3>
                <small class="text-muted">Virtual Schedule Auto-Generated</small>
            </div>
            <div class="cal-nav-buttons">
                <button class="btn-nav shadow-sm">Aksi</button>
                <button class="btn-nav shadow-sm">Aksi</button>
            </div>
        </div>

        <div class="calendar-grid-modern">
            <!-- Header Hari -->
            <div class="day-label text-danger">Sun</div>
            <div class="day-label">Mon</div>
            <div class="day-label">Tue</div>
            <div class="day-label">Wed</div>
            <div class="day-label">Thu</div>
            <div class="day-label">Fri</div>
            <div class="day-label text-warning">Sat</div>

            <!-- Empty cells for start of month -->
            @for($e = 0; $e < $firstDayOfWeek; $e++)
                <div class="grid-day-cell empty-cell"></div>
            @endfor

            <!-- Actual days -->
            @for ($i = 1; $i <= $daysInMonth; $i++)
                @php
                    $currentDayOfWeek = ($firstDayOfWeek + $i - 1) % 7;
                    $isWeekend = ($currentDayOfWeek == 0 || $currentDayOfWeek == 6);
                    $isToday = ($i == $todayDate);
                @endphp

                <div class="grid-day-cell {{ $isWeekend ? 'weekend-cell' : '' }} {{ $isToday ? 'today-cell' : '' }}">
                    <span class="date-num {{ $isToday ? 'text-white bg-primary rounded-circle' : '' }}">{{ $i }}</span>

                    @if($isWeekend && $defaultTab)
                        <div class="event-tag shadow-sm" style="background-color: #DD9E59; color: white;">
                            <div class="fw-bold text-truncate">{{ $isSeniTari ? 'Tari' : 'Karawitan' }}</div>
                            <small class="fw-semibold opacity-100">{{ $isSeniTari ? '10:00-13:00' : '13:00-15:00' }}</small>
                        </div>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    <!-- Area Info/Reminder (Kanan) -->
    <div class="info-section">
        <!-- Reminder Card -->
        <div class="card side-card reminder-card glass-panel section-card">
            <h6 class="fw-bold mb-4" style="color: #503422;">Reminder Bulan Ini</h6>
            <div class="reminder-item mb-4">
                <div class="icon-circle shadow-sm"></div>
                <div>
                    <p class="m-0 fw-bold small text-dark">Minggu Ini: Evaluasi Gerak</p>
                    <small class="text-muted">Fokus pada ritme ketukan</small>
                </div>
            </div>
            <div class="reminder-item">
                <div class="icon-circle inactive border"></div>
                <div>
                    <p class="m-0 fw-bold small text-dark">Minggu Depan: Sinkronisasi</p>
                    <small class="text-muted">Latihan bersama pemusik</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .main-schedule-wrapper {
        display: flex;
        gap: 24px;
        font-family: 'Poppins', sans-serif;
    }
    
    /* Glassmorphism & Clean Rounded UI */
    .glass-panel {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }

    .calendar-section { flex: 1; padding: 25px 30px; }
    .info-section { width: 320px; }

    .btn-nav {
        background: #fff;
        border: 1px solid #f0f0f0;
        width: 36px;
        height: 36px;
        border-radius: 12px;
        color: #A47251;
        transition: all 0.2s;
    }
    .btn-nav:hover { background: #A47251; color: white; transform: translateY(-2px); }

    /* Calendar Grid */
    .calendar-grid-modern {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 12px; /* Space between cells for clean look */
    }
    
    .day-label { 
        text-align: center; 
        padding: 10px 0; 
        font-weight: 700; 
        color: #8E98A8; 
        font-size: 0.85rem; 
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .grid-day-cell { 
        background: #FDFBF9; 
        min-height: 110px; 
        padding: 8px; 
        border-radius: 16px; 
        position: relative;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }
    
    .grid-day-cell:not(.empty-cell):hover {
        background: #fff;
        border-color: #f0e9e4;
        box-shadow: 0 8px 15px rgba(164, 114, 81, 0.08);
        transform: translateY(-2px);
    }
    
    .empty-cell { background: transparent; }
    
    /* Weekend Highlight */
    .weekend-cell { 
        background: #DD9E59; 
        border: 1px solid rgba(221, 158, 89, 0.5);
    }
    .weekend-cell:hover {
        background: #c7863d !important;
        border-color: #A47251 !important;
    }

    /* Today Highlight */
    .today-cell {
        background: rgba(164, 114, 81, 0.04);
        border: 2px solid #A47251 !important;
    }

    .date-num { 
        font-size: 0.85rem; 
        color: #4A5568; 
        font-weight: 600;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px; 
    }

    /* Tags */
    .event-tag {
        font-size: 0.7rem;
        padding: 6px 10px;
        border-radius: 10px;
        margin-top: 4px;
        line-height: 1.3;
        transition: transform 0.2s;
        cursor: pointer;
    }
    .event-tag:hover { transform: scale(1.03); }
    
    .tag-primary { 
        background: linear-gradient(135deg, #A47251, #8e6245); 
        color: white; 
    }
    .tag-secondary { 
        background: linear-gradient(135deg, #DD9E59, #c7863d); 
        color: white; 
    }

    /* Cards Kanan */
    .side-card { padding: 25px; }
    .reminder-item { display: flex; gap: 15px; align-items: center; }
    .icon-circle { 
        width: 42px; height: 42px; 
        border-radius: 12px; 
        background: rgba(164, 114, 81, 0.1); 
        color: #A47251; 
        display: flex; align-items: center; justify-content: center; 
        font-size: 1.2rem;
    }
    .icon-circle.inactive { background: #f8f9fa; color: #adb5bd; }
</style>
