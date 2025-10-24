<?php

function createTable(array $records, bool $withHeaders=false): string {
    $htmlString = '<table>';

    if ($withHeaders) {
        $htmlString .= '<thead><tr>';

        $htmlString .= '<th>Image</th>';

        foreach (array_keys($records[0]) as $header) {
            if ($header === 'id') {
                continue;
            }
            $htmlString .= '<th>' . $header . '</th>';
        }

        $htmlString .= '<th>Action</th>';
        $htmlString .= '</tr></thead>';
    }

    $htmlString .= '<tbody>';
    foreach ($records as $record) {
        $htmlString .= '<tr>';

        $htmlString .= '<td><img width="64px" height="auto" src="../assets/images/' . $record['pokedex_nr'] . '.png" alt="image of ' . $record['name']. '"></td>';

        foreach($record as $key => $value) {
            if ($key === 'id') {
                continue;
            }
            if ($key === 'caught') {
                $caughtSVG = $value == 0 ? '<img class="pokeball pokeball--inactive" src="../assets/svg/pokeball_inactive.svg" alt="caught pokeball inactive">' : '<img class="pokeball pokeball--active" src="../assets/svg/pokeball_active.svg" alt="caught pokeball active">';
                $htmlString .= '<td>' . $caughtSVG . '</td>';
            }
            else if ($key === 'type_1') {
                $typeString = '
                    <div class="banner__content">
                        <span class="banner__icon type--' . str_replace('ä', 'ae', strtolower($value)) . '">
                            ' . TYPE_SVG[str_replace('ä', 'ae', strtolower($value))] . '
                            </span>
                        <span class="banner__text type--' . str_replace('ä', 'ae', strtolower($value)) . '">' . $value . '</span>
                    </div>';

                $htmlString .= '<td>' . $typeString . '</td>';
            }
            else if ($key === 'type_2' && $value !== '–') {
                $typeString = '
                    <div class="banner__content">
                        <span class="banner__icon type--' . str_replace('ä', 'ae', strtolower($value)) . '">
                            ' . TYPE_SVG[str_replace('ä', 'ae', strtolower($value))] . '
                        </span>
                        <span class="banner__text type--' . str_replace('ä', 'ae', strtolower($value)) . '">' . $value . '</span>
                    </div>';

                $htmlString .= '<td>' . $typeString . '</td>';
            }
            else {
                $htmlString .= '<td>' . $value . '</td>';
            }
        }
        $actionLink = "<td><a href='/pokemon/show/{$record['id']}'><button>Details</button></a></td>";
        $htmlString .= $actionLink;
        $htmlString .= '</tr>';
    }
    $htmlString .= '</tbody>';

    return $htmlString;
}