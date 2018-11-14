<?php
/*
 -------------------------------------------------------------------------
 TaskTimer plugin for GLPI
 Copyright (C) 2018 by the TaskTimer Development Team.

 https://github.com/pluginsGLPI/tasktimer
 -------------------------------------------------------------------------

 LICENSE

 This file is part of TaskTimer.

 TaskTimer is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 TaskTimer is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with TaskTimer. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

/**
 * Plugin install process
 *
 * @return boolean
 */
function plugin_tasktimer_install() {
   return true;
}

/**
 * Plugin uninstall process
 *
 * @return boolean
 */
function plugin_tasktimer_uninstall() {
   return true;
}

function display_timer(array $params){
    $item = $params['item'];
    if ($item::getType() === TicketTask::getType()) {

        echo "<tr class=''><td>";
        echo "Timer";
        echo "</td>";
        echo "<td>";
        echo "<span id=\"hours\">00</span> : <span id=\"minutes\">00</span> : <span id=\"seconds\">00</span> ";
        echo "<span id=\"start_pause_resume\" class=\"vsubmit\">Start</span> <span id=\"reset_stopwatch\" class=\"vsubmit\">Reset</span>";
        echo "</td>";
        echo "</tr>";
        ?>
        <script>
            $(function() {

                var hours = minutes = seconds = milliseconds = 0;
                var prev_hours = prev_minutes = prev_seconds = prev_milliseconds = undefined;
                var timeUpdate;

                // Start/Pause/Resume button onClick
                $("#start_pause_resume").click(function(){
                    // Start button
                    if($(this).text() == "Start"){  // check button label
                        $(this).html("Pause");
                        updateTime(0,0,0,0);
                    }
                    // Pause button
                    else if($(this).text() == "Pause"){
                        clearInterval(timeUpdate);
                        $(this).html("Resume");
                    }
                    // Resume button
                    else if($(this).text() == "Resume"){
                        prev_hours = parseInt($("#hours").html());
                        prev_minutes = parseInt($("#minutes").html());
                        prev_seconds = parseInt($("#seconds").html());
                        prev_milliseconds = parseInt($("#milliseconds").html());

                        updateTime(prev_hours, prev_minutes, prev_seconds, prev_milliseconds);

                        $(this).html("Pause");
                    }
                });

                // Reset button onClick
                $("#reset_stopwatch").click(function(){
                    if(timeUpdate) clearInterval(timeUpdate);
                    setStopwatch(0,0,0,0);
                    $("#start_pause_resume").html("Start");
                });

                // Update time in stopwatch periodically - every 25ms
                function updateTime(prev_hours, prev_minutes, prev_seconds, prev_milliseconds){
                    var startTime = new Date();    // fetch current time

                    timeUpdate = setInterval(function () {
                        var timeElapsed = new Date().getTime() - startTime.getTime();    // calculate the time elapsed in milliseconds

                        // calculate hours
                        hours = parseInt(timeElapsed / 1000 / 60 / 60) + prev_hours;

                        // calculate minutes
                        minutes = parseInt(timeElapsed / 1000 / 60) + prev_minutes;
                        if (minutes > 60) minutes %= 60;

                        // calculate seconds
                        seconds = parseInt(timeElapsed / 1000) + prev_seconds;
                        if (seconds > 60) seconds %= 60;

                        // calculate milliseconds
                        milliseconds = timeElapsed + prev_milliseconds;
                        if (milliseconds > 1000) milliseconds %= 1000;

                        // set the stopwatch
                        setStopwatch(hours, minutes, seconds, milliseconds);

                    }, 25); // update time in stopwatch after every 25ms

                }

                // Set the time in stopwatch
                function setStopwatch(hours, minutes, seconds, milliseconds){
                    $("#hours").html(prependZero(hours, 2));
                    $("#minutes").html(prependZero(minutes, 2));
                    $("#seconds").html(prependZero(seconds, 2));
                    $("#milliseconds").html(prependZero(milliseconds, 3));
                }

                // Prepend zeros to the digits in stopwatch
                function prependZero(time, length) {
                    time = new String(time);    // stringify time
                    return new Array(Math.max(length - time.length + 1, 0)).join("0") + time;
                }
            });
        </script>
        <?php
    }
}
