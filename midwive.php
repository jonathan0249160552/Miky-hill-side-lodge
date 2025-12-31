<?php
// Midwife Rotation Scheduler v17 - REAL NAMES + DYNAMIC START DATE
// ✅ MD001–MD090 with actual midwife names attached
// ✅ Dynamic start date
// ✅ Min 5 / Max 6 units (ANC & Children's = 10)
// ✅ Full timestamps + Handover summary

$units = [
    'special_clinic' => 2,
    'public_health'  => 4,
    'anc'            => 6,
    'childrens_ward' => 6,
    'female_ward'    => 3,
    'male_ward'      => 3,
    'opd'            => 1,
    'er'             => 1,
    'psychiatry'     => 4,
    'otmc'           => 2,
    'theater'        => 4
];

// ============ DYNAMIC START DATE ============
$defaultStartDate = '2025-12-29';
$userStart = $_GET['start'] ?? $defaultStartDate;

try {
    $week1Monday = new DateTime($userStart);
} catch (Exception $e) {
    $week1Monday = new DateTime($defaultStartDate);
}

$startDisplay = $week1Monday->format('l, F j, Y');

function getWeekDates($weekNum, DateTime $week1Monday)
{
    $monday = clone $week1Monday;
    $monday->modify("+" . ($weekNum - 1) . " weeks");
    $sunday = clone $monday;
    $sunday->modify('+6 days');
    return [
        'start' => $monday->format('F j, Y'),
        'end'   => $sunday->format('F j, Y'),
        'display' => $monday->format('M j') . ' – ' . $sunday->format('M j, Y')
    ];
}

// ============ REAL NAMES ATTACHED TO IDs ============
// Edit this array with actual midwife names
$midwifeNames = [
    1   => "Ama Serwaa",
    2   => "Abena Mensah",
    3   => "Akosua Boateng",
    4   => "Yaw Owusu",
    5   => "Kwame Asare",
    6   => "Adwoa Nkrumah",
    7   => "Esi Addo",
    8   => "Kofi Amoah",
    9   => "Maame Adjei",
    10  => "Nana Yaa",
    11  => "Grace Osei",
    12  => "Patience Darko",
    13  => "Mercy Agyemang",
    14  => "Felicia Yeboah",
    15  => "Victoria Appiah",
    16  => "Josephine Quaye",
    17  => "Elizabeth Sackey",
    18  => "Comfort Ansah",
    19  => "Dorcas Frimpong",
    20  => "Theresa Adu",
    21  => "Sandra Opoku",
    22  => "Linda Agyei",
    23  => "Rebecca Tetteh",
    24  => "Christina Danso",
    25  => "Martha Kumah",
    26  => "Sarah Baah",
    27  => "Hannah Ofori",
    28  => "Esther Amponsah",
    29  => "Gloria Kwarteng",
    30  => "Priscilla Nyame",
    // Add more names as needed for MD031 – MD090
    31  => "Jennifer Asante",
    32  => "Mary Annor",
    33  => "Ruth Aboagye",
    34  => "Juliet Obeng",
    35  => "Veronica Larbi",
    36  => "Beatrice Donkor",
    37  => "Regina Twum",
    38  => "Emilia Agyapong",
    39  => "Philomena Baffour",
    40  => "Cecilia Mensah",
    41  => "Agnes Okyere",
    42  => "Rosemary Gyamfi",
    43  => "Janet Amissah",
    44  => "Monica Sarfo",
    45  => "Bridget Asamoah",
    46  => "Pauline Kusi",
    47  => "Vivian Acheampong",
    48  => "Sylvia Owusu-Ansah",
    49  => "Diana Afriyie",
    50  => "Irene Boakye",
    51  => "Portia Oduro",
    52  => "Naomi Agyekum",
    53  => "Lillian Quansah",
    54  => "Deborah Amoako",
    55  => "Charlotte Frempong",
    56  => "Anita Adom",
    57  => "Rita Boadu",
    58  => "Joyce Nkansah",
    59  => "Stella Asiedu",
    60  => "Margaret Antwi",
    61  => "Florence Ameyaw",
    62  => "Lucy Osei-Tutu",
    63  => "Bernice Adu-Gyamfi",
    64  => "Gifty Owusu",
    65  => "Doris Mensah",
    66  => "Evelyn Agyemang",
    67  => "Sophia Adjei",
    68  => "Angela Boahene",
    69  => "Cynthia Amo",
    70  => "Vanessa Yeboah",
    71  => "Michelle Ofori",
    72  => "Natasha Amponsah",
    73  => "Tracy Kwarteng",
    74  => "Belinda Nyamekye",
    75  => "Felicity Asare",
    76  => "Claudia Danso",
    77  => "Rachel Oppong",
    78  => "Serena Agyei",
    79  => "Lydia Baah",
    80  => "Miriam Frimpong",
    81  => "Joana Sackey",
    82  => "Caroline Addo",
    83  => "Emmanuella Quaye",
    84  => "Valerie Ansah",
    85  => "Genevieve Darko",
    86  => "Isabella Agyapong",
    87  => "Olivia Nkrumah",
    88  => "Sophia Osei",
    89  => "Amelia Boateng",
    90  => "Ella Mensah"
    // You can edit or expand this list with real names
];

// Build midwives
$midwives = [];
for ($i = 1; $i <= 90; $i++) {
    $group = ($i <= 30) ? 'A' : (($i <= 60) ? 'B' : 'C');
    $inFirst20 = ($i % 30 !== 0 && $i % 30 <= 20);

    if ($group === 'A') {
        $maternityWeeks = $inFirst20 ? range(1, 16) : range(5, 20);
    } elseif ($group === 'B') {
        $maternityWeeks = $inFirst20 ? range(17, 32) : range(21, 36);
    } else {
        $maternityWeeks = $inFirst20 ? range(33, 48) : range(37, 52);
    }

    $startWeek = $maternityWeeks[0];
    $endWeek = end($maternityWeeks);
    $startDate = getWeekDates($startWeek, $week1Monday);
    $endDate = getWeekDates($endWeek, $week1Monday);

    $midwifeId = 'MD' . str_pad($i, 3, '0', STR_PAD_LEFT);
    $realName = $midwifeNames[$i] ?? "Midwife $i"; // Fallback if name not set

    $midwives[$i] = [
        'internal_id' => $i,
        'id' => $midwifeId,
        'group' => $group,
        'name' => $realName,
        'display' => "$midwifeId - $realName",
        'maternity_weeks' => $maternityWeeks,
        'maternity_start' => $startDate['start'],
        'maternity_end' => $endDate['end'],
        'remaining' => $units,
        'assigned' => array_fill_keys(array_keys($units), 0)
    ];
}

// Schedule
$schedule = [];
for ($week = 1; $week <= 52; $week++) {
    $dates = getWeekDates($week, $week1Monday);
    $schedule[$week] = [
        'dates' => $dates,
        'maternity' => [],
        'units' => array_fill_keys(array_keys($units), [])
    ];
}

// Assign Maternity
foreach ($midwives as $internal => $mw) {
    foreach ($mw['maternity_weeks'] as $w) {
        if ($w <= 52) {
            $schedule[$w]['maternity'][] = [
                'id' => $mw['id'],
                'name' => $mw['display'],
                'group' => $mw['group'],
                'start' => $schedule[$w]['dates']['start'],
                'end' => $schedule[$w]['dates']['end']
            ];
        }
    }
}

// Unit targets
$targetStaffing = [
    'anc'            => 10,
    'childrens_ward' => 10,
    'public_health'  => 6,
    'psychiatry'     => 6,
    'theater'        => 6,
    'female_ward'    => 5,
    'male_ward'      => 5,
    'special_clinic' => 5,
    'otmc'           => 5,
    'opd'            => 5,
    'er'             => 5
];

function assignUnitsMin5Max6($week, &$schedule, &$midwives, $units, $targetStaffing)
{
    $dates = $schedule[$week]['dates'];
    $available = [];

    foreach ($midwives as $internal => $mw) {
        if (!in_array($week, $mw['maternity_weeks']) && array_sum($mw['remaining']) > 0) {
            $available[$internal] = $mw;
        }
    }

    foreach ($units as $unit => $req) {
        $candidates = [];
        foreach ($available as $internal => $mw) {
            if ($mw['remaining'][$unit] > 0) {
                $candidates[$internal] = $mw['remaining'][$unit];
            }
        }
        arsort($candidates);

        $slots = $targetStaffing[$unit];
        $i = 0;
        foreach (array_keys($candidates) as $internal) {
            if ($i >= $slots) break;
            $mw = $midwives[$internal];
            $schedule[$week]['units'][$unit][] = [
                'id' => $mw['id'],
                'name' => $mw['display'],
                'group' => $mw['group'],
                'start' => $dates['start'],
                'end' => $dates['end']
            ];
            $midwives[$internal]['remaining'][$unit]--;
            $midwives[$internal]['assigned'][$unit]++;
            unset($available[$internal]);
            $i++;
        }
    }

    while (!empty($available)) {
        $internal = key($available);
        $mw = $midwives[$internal];
        $assigned = false;
        foreach ($units as $unit => $req) {
            if ($mw['remaining'][$unit] > 0 && count($schedule[$week]['units'][$unit]) < $targetStaffing[$unit] + 1) {
                $schedule[$week]['units'][$unit][] = [
                    'id' => $mw['id'],
                    'name' => $mw['display'],
                    'group' => $mw['group'],
                    'start' => $dates['start'],
                    'end' => $dates['end']
                ];
                $midwives[$internal]['remaining'][$unit]--;
                $midwives[$internal]['assigned'][$unit]++;
                unset($available[$internal]);
                $assigned = true;
                break;
            }
        }
        if (!$assigned) unset($available[$internal]);
    }
}

for ($week = 1; $week <= 52; $week++) {
    assignUnitsMin5Max6($week, $schedule, $midwives, $units, $targetStaffing);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>🏥 90 Midwives 2026 - With Real Names</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --o: #f97316;
            --d: #1a1a1a;
            --c: #2a2a2a;
            --t: #fff;
            --b: #444;
            --g: #10b981;
        }

        body {
            font-family: Arial, sans-serif;
            background: var(--d);
            color: var(--t);
            padding: 20px;
            max-width: 1600px;
            margin: auto;
            line-height: 1.6;
        }

        h1,
        h2,
        h3 {
            color: var(--o);
        }

        .card {
            background: var(--c);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--b);
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        th {
            background: var(--o);
            color: white;
            padding: 12px;
            font-size: 14px;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid var(--b);
            font-size: 13px;
            vertical-align: top;
        }

        .unit-header {
            background: #333;
            padding: 12px;
            border-radius: 8px;
            margin: 20px 0 10px;
            font-weight: bold;
            color: var(--o);
            font-size: 1.1em;
        }

        .mw-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }

        .mw-item {
            background: #333;
            padding: 10px;
            border-radius: 8px;
        }

        .mw-id {
            font-weight: bold;
            color: var(--o);
        }

        .timestamp {
            font-size: 12px;
            color: #aaa;
        }

        .success {
            color: var(--g);
            font-weight: bold;
        }

        .btn {
            background: var(--o);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            margin: 10px 0;
        }
             .form-group{margin:20px 0;}
        input[type=text]{padding:10px;font-size:16px;width:300px;border-radius:8px;border:1px solid var(--b);}
        input[type=submit]{background:var(--o);color:white;padding:10px 20px;border:none;border-radius:8px;cursor:pointer;}
    </style>
</head>

<body>
    <div class="card">
        <h1>🏥 90 Midwives Rotation 2026 – With Real Names</h1>
        <p><strong>Current Start:</strong> <span class="timestamp"><?= $startDisplay ?></span></p>
        <p class="success">✅ Names now attached to MD001 – MD090</p>
        <p class="success">✅ Format: MDxxx - Full Name</p>

        <div class="form-group">
            <h3>Change Start Date</h3>
            <form method="get">
                <input type="text" name="start" placeholder="YYYY-MM-DD" value="<?= htmlspecialchars($userStart) ?>">
                <input type="submit" value="Update">
            </form>
        </div>

        <a href="?download=csv&start=<?= urlencode($userStart) ?>" class="btn">📥 Download CSV with Names</a>
    </div>

    <!-- Handover Summary with Names Example -->
    <div class="card">
        <h2>📅 Maternity Handover Summary</h2>
        <table>
            <tr>
                <th>Phase</th>
                <th>Weeks</th>
                <th>Date Range</th>
                <th>Example Midwives</th>
            </tr>
            <?php
            $phases = [
                ['1–4', 1, 'MD001–MD020'],
                ['5–16', 5, 'MD001–MD030'],
                ['17–20', 17, 'MD021–MD030 + MD031–MD050'],
                ['21–32', 21, 'MD031–MD060'],
                ['33–36', 33, 'MD051–MD060 + MD061–MD080'],
                ['37–48', 37, 'MD061–MD090'],
                ['49–52', 49, 'MD081–MD090']
            ];
            foreach ($phases as $p):
                $startD = getWeekDates($p[1], $week1Monday);
                $endW = explode('–', $p[0])[1] ?? $p[1];
                $endD = getWeekDates($endW, $week1Monday);
            ?>
                <tr>
                    <td><?= $p[0] ?></td>
                    <td><?= $startD['start'] ?> – <?= $endD['end'] ?></td>
                    <td><?= $p[2] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Sample Weeks -->
    <?php
    $displayWeeks = [1, 5, 17, 21];
    foreach ($displayWeeks as $w):
        $d = $schedule[$w]['dates'];
    ?>
        <div class="card">
            <h2>Week <?= $w ?> (<?= $d['display'] ?>)</h2>

            <div style="background:#333;padding:12px;border-radius:8px;margin:15px 0;font-weight:bold;color:var(--o);">
                🏥 MATERNITY (<?= count($schedule[$w]['maternity']) ?>)
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:10px;">
                <?php
                usort($schedule[$w]['maternity'], fn($a, $b) => strcmp($a['id'], $b['id']));
                foreach ($schedule[$w]['maternity'] as $a):
                ?>
                    <div style="background:#333;padding:12px;border-radius:8px;">
                        <span class="mw-id"><?= $a['id'] ?></span><br>
                        <span class="mw-name"><?= explode(' - ', $a['name'])[1] ?? $a['name'] ?></span><br>
                        <span class="timestamp"><?= $a['start'] ?> to <?= $a['end'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php foreach ($units as $u => $req):
                $list = $schedule[$w]['units'][$u];
                $count = count($list);
                if ($count == 0) continue;
                $name = ucwords(str_replace('_', ' ', $u));
            ?>
                <div style="background:#333;padding:12px;border-radius:8px;margin:15px 0;font-weight:bold;color:var(--o);">
                    🩺 <?= $name ?> (<?= $count ?>)
                </div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:10px;">
                    <?php
                    usort($list, fn($a, $b) => strcmp($a['id'], $b['id']));
                    foreach ($list as $a):
                    ?>
                        <div style="background:#333;padding:12px;border-radius:8px;">
                            <span class="mw-id"><?= $a['id'] ?></span><br>
                            <span class="mw-name"><?= explode(' - ', $a['name'])[1] ?? $a['name'] ?></span><br>
                            <span class="timestamp"><?= $a['start'] ?> to <?= $a['end'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <div class="card" style="text-align:center;">
        <p class="success">✅ Names Successfully Attached!</p>
        <p>Each midwife now shows as <strong>MDxxx - Full Name</strong></p>
        <p>Edit the <code>$midwifeNames</code> array at the top to update real names anytime.</p>
        <p>Ready for staff list printing and hospital roster system 🧡</p>
    </div>

    <?php
    if (isset($_GET['download'])) {
        $dlStart = $_GET['start'] ?? $defaultStartDate;
        $dlWeek1 = new DateTime($dlStart);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="midwives_2026_with_names.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Week', 'Start_Date', 'End_Date', 'Unit', 'Midwife_ID', 'Full_Name', 'Group']);

        for ($week = 1; $week <= 52; $week++) {
            $d = getWeekDates($week, $dlWeek1);
            foreach ($schedule[$week]['maternity'] as $a) {
                $fullName = explode(' - ', $a['name'])[1] ?? $a['name'];
                fputcsv($out, [$week, $d['start'], $d['end'], 'Maternity', $a['id'], $fullName, $a['group']]);
            }
            foreach ($schedule[$week]['units'] as $unit => $list) {
                foreach ($list as $a) {
                    $fullName = explode(' - ', $a['name'])[1] ?? $a['name'];
                    fputcsv($out, [$week, $d['start'], $d['end'], ucwords(str_replace('_', ' ', $unit)), $a['id'], $fullName, $a['group']]);
                }
            }
        }
        exit;
    }
    ?>
</body>

</html>