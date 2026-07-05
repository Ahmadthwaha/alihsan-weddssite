<?php 
require_once 'db.php';
include 'header.php';

// Get month and year from query string or default to current month/year
$month = isset($_GET['month']) ? intval($_GET['month']) : intval(date('n'));
$year = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));

// Validate inputs
if ($month < 1 || $month > 12) { $month = intval(date('n')); }
if ($year < 2000 || $year > 2100) { $year = intval(date('Y')); }

$first_day_of_month = mktime(0, 0, 0, $month, 1, $year);
$number_of_days = date('t', $first_day_of_month);
$date_components = getdate($first_day_of_month);
$month_name = $date_components['month'];
$day_of_week = $date_components['wday']; // 0 (Sunday) to 6 (Saturday)

// Prev & Next Month buttons
$prev_month = $month - 1;
$prev_year = $year;
if ($prev_month == 0) {
    $prev_month = 12;
    $prev_year--;
}

$next_month = $month + 1;
$next_year = $year;
if ($next_month == 13) {
    $next_month = 1;
    $next_year++;
}

// Fetch events for this month
$events_by_day = [];
$all_this_month_events = []; // For detailed listing below or modals

if (DB_ACTIVE) {
    $start_date = sprintf("%04d-%02d-01", $year, $month);
    $end_date = sprintf("%04d-%02d-%02d", $year, $month, $number_of_days);
    $res = $conn->query("SELECT * FROM events WHERE event_date >= '$start_date' AND event_date <= '$end_date' ORDER BY event_time ASC");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $day = intval(date('j', strtotime($row['event_date'])));
            $events_by_day[$day][] = $row;
            $all_this_month_events[] = $row;
        }
    }
} else {
    // Fallback
    $file = getFallbackFile();
    $data = json_decode(file_get_contents($file), true);
    $fallback_events = isset($data['events']) ? $data['events'] : [];
    // Sort fallback events by time
    usort($fallback_events, function($a, $b) {
        return strcmp($a['event_time'] ?? '', $b['event_time'] ?? '');
    });
    foreach ($fallback_events as $ev) {
        $ev_time = strtotime($ev['event_date']);
        if (date('Y', $ev_time) == $year && date('n', $ev_time) == $month) {
            $day = intval(date('j', $ev_time));
            $events_by_day[$day][] = $ev;
            $all_this_month_events[] = $ev;
        }
    }
}
?>

<style>
    .calendar-container {
        background: #fff;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border-radius: 8px;
        padding: 30px;
    }
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .calendar-title {
        color: #1b365d;
        font-weight: 800;
        margin: 0;
    }
    .calendar-nav-btn {
        background-color: #1b365d;
        color: #fff;
        border: none;
        padding: 8px 18px;
        border-radius: 4px;
        font-weight: 600;
        text-decoration: none;
        transition: background-color 0.2s;
    }
    .calendar-nav-btn:hover {
        background-color: #c5a85c;
        color: #1b365d;
    }
    .calendar-table {
        width: 100%;
        border-collapse: collapse;
    }
    .calendar-table th {
        background-color: #1b365d;
        color: white;
        text-align: center;
        padding: 12px;
        font-weight: bold;
        border: 1px solid #dee2e6;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
    }
    .calendar-table td {
        width: 14.28%;
        height: 110px;
        vertical-align: top;
        padding: 8px;
        border: 1px solid #dee2e6;
        background-color: #ffffff;
        position: relative;
    }
    .calendar-table td.empty-cell {
        background-color: #f8f9fa;
    }
    .calendar-table td.today-cell {
        background-color: #fffdec;
        border: 2px solid #c5a85c !important;
    }
    .calendar-table .day-num {
        font-weight: 700;
        color: #1b365d;
        font-size: 15px;
        margin-bottom: 6px;
        display: block;
    }
    .calendar-table .event-badge {
        font-size: 10px;
        background-color: #1b365d;
        color: white;
        padding: 4px 6px;
        border-radius: 3px;
        margin-bottom: 4px;
        cursor: pointer;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: block;
        border-left: 3px solid #c5a85c;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
    }
    .calendar-table .event-badge:hover {
        background-color: #c5a85c;
        color: #1b365d;
    }
    @media (max-width: 768px) {
        .calendar-table th {
            padding: 5px;
            font-size: 10px;
        }
        .calendar-table td {
            height: 80px;
            padding: 4px;
        }
        .calendar-table .day-num {
            font-size: 12px;
        }
        .calendar-table .event-badge {
            font-size: 8px;
            padding: 2px;
        }
    }
</style>

<!-- banner area start -->
<div class="rts-breadcrumb-area" style="background: linear-gradient(135deg, #1b365d 0%, #0d1e3d 100%); padding: 80px 0; position: relative;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-inner text-center" style="position: relative; z-index: 2;">
                    <h1 class="title text-white" style="font-size: 45px; font-weight: 800; text-transform: uppercase;">Campus Events</h1>
                    <ul class="breadcrumb-navigation" style="display: flex; justify-content: center; gap: 10px; list-style: none; padding: 0; color: rgba(255,255,255,0.8); font-size: 14px;">
                        <li><a href="index.php" style="color: #fff; text-decoration: none;">Home</a></li>
                        <li>/</li>
                        <li class="active" style="color: #c5a85c;">Events Calendar</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- banner area end -->

<!-- content area start -->
<section class="events-page py-5 my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="calendar-container">
                    
                    <!-- Calendar Header Navigation -->
                    <div class="calendar-header">
                        <a href="events.php?month=<?= $prev_month ?>&year=<?= $prev_year ?>" class="calendar-nav-btn"><i class="fa fa-chevron-left me-2"></i> Previous</a>
                        <h3 class="calendar-title text-center text-uppercase fw-bold"><?= $month_name . ' ' . $year ?></h3>
                        <a href="events.php?month=<?= $next_month ?>&year=<?= $next_year ?>" class="calendar-nav-btn">Next <i class="fa fa-chevron-right ms-2"></i></a>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="table-responsive">
                        <table class="calendar-table">
                            <thead>
                                <tr>
                                    <th>Sun</th>
                                    <th>Mon</th>
                                    <th>Tue</th>
                                    <th>Wed</th>
                                    <th>Thu</th>
                                    <th>Fri</th>
                                    <th>Sat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    // 1. Output empty cells until the first day of the week
                                    for ($i = 0; $i < $day_of_week; $i++) {
                                        echo '<td class="empty-cell"></td>';
                                    }

                                    // 2. Output days of the month
                                    $current_day = 1;
                                    $day_cursor = $day_of_week;

                                    while ($current_day <= $number_of_days) {
                                        if ($day_cursor == 7) {
                                            echo '</tr><tr>';
                                            $day_cursor = 0;
                                        }

                                        $is_today = ($current_day == intval(date('j')) && $month == intval(date('n')) && $year == intval(date('Y')));
                                        $td_class = $is_today ? 'today-cell' : '';

                                        echo '<td class="' . $td_class . '">';
                                        echo '<span class="day-num">' . $current_day . '</span>';

                                        // Display events for this specific day
                                        if (isset($events_by_day[$current_day])) {
                                            foreach ($events_by_day[$current_day] as $ev) {
                                                $ev_id = $ev['id'];
                                                $ev_title = htmlspecialchars($ev['title']);
                                                echo '<a href="#" class="event-badge" data-bs-toggle="modal" data-bs-target="#eventModal' . $ev_id . '">' . $ev_title . '</a>';
                                            }
                                        }

                                        echo '</td>';
                                        $current_day++;
                                        $day_cursor++;
                                    }

                                    // 3. Output remaining empty cells to complete the last week row
                                    if ($day_cursor < 7) {
                                        for ($i = $day_cursor; $i < 7; $i++) {
                                            echo '<td class="empty-cell"></td>';
                                        }
                                    }
                                    ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- content area end -->

<!-- Modals for Event Details -->
<?php foreach ($all_this_month_events as $ev): ?>
    <div class="modal fade" id="eventModal<?= $ev['id'] ?>" $_GET tabindex="-1" aria-labelledby="eventModalLabel<?= $ev['id'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 8px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background-color: #1b365d; color: white; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                    <h5 class="modal-title fw-bold" id="eventModalLabel<?= $ev['id'] ?>"><?= htmlspecialchars($ev['title']) ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p style="font-size: 14px; color: #c5a85c; font-weight: 700; margin-bottom: 15px;">
                        <span class="me-3"><i class="fa fa-calendar me-2"></i><?= date('F d, Y', strtotime($ev['event_date'])) ?></span>
                        <?php if (!empty($ev['event_time'])): ?>
                            <span><i class="fa fa-clock me-2"></i><?= date('h:i A', strtotime($ev['event_time'])) ?></span>
                        <?php endif; ?>
                    </p>
                    <p style="font-size: 15px; color: #444; line-height: 1.6; white-space: pre-line;">
                        <?= htmlspecialchars($ev['description'] ?? 'No description provided for this campus event.') ?>
                    </p>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #eee;">
                    <button type="button" class="btn text-white fw-bold" style="background-color: #1b365d;" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php include 'footer.php'; ?>
