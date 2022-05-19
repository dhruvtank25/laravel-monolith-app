<div class="availability_date_wrap">
    <div class="row">
        <div class="col-md-12 mb-5 period_date_wrap">
            <div class="datepicker"></div>
        </div>
        <div class="col-md-12 select_avail mb-4">
            <h6 class="mb-2">Ausgewählte Verfügbarkeit</h6>
            <p id="availability_select_date"></p>
        </div>
        <div id="availabilities_skeleton" class="col-md-12 period_hidden">
            <div class="availability" data-id="0" data-recurring="single">
                <div class="col-md-12 mb-4">
                    <h6 class="mb-2">Zeitraum <span class="availability_no">1</span></h6>
                    <div class="range_period_slider">
                        <input type="text" class="period_range_slider"/>
                        <p class="period_range_val mt-3 period_from" data-time="04:00">04:00 Uhr</p>
                        <p class="mt-3">bis</p>
                        <p class="period_range_val mt-3 period_to" data-time="23:00">23:00 Uhr</p>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="ano_counselling crt_series">
                        <div class="ano_select">
                            <div class="selection_wrapper">
                                <!-- Rounded switch -->
                                <label class="switch">
                                    <input type="checkbox" class="period_switch series_switch">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <h6>Serie erstellen</h6> <span class="series_from"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3 mt-3 term_wrapper" style="display:none;">
                    <div class="mb-3 know_category period_cate alldayseries">
                        <input type="radio" name="series1" id="radio7" class="css-checkbox" value="daily">
                        <label for="radio7" class="css-label">Täglich</label>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3 know_category period_cate weekdayseries">
                            <input type="radio" name="series1" id="radio8" class="css-checkbox" value="weekly">
                            <label for="radio8" class="css-label">Termin wiederholt sich für</label>
                        </div>
                        <div class="col-md-6 mb-3 week_select">
                            <input type="number" name="period_week" id="period_week" class="form-control mr-2" value="2" min="2">
                            <label for="period_week">Wochen</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <button type="button" class="btn orange_background_btn delete_period">Löschen</button>
                </div>
            </div>
        </div>
        <div id="availability_div" class="col-md-12">
        </div>
        <div class="furthur_period_btn col-md-12 mb-4 pb-4">
            <button type="button" class="btn blk_bor_btn mr-3 add_period">+ Weiterer Zeitraum</button>
            {{-- <button type="button" class="btn orange_background_btn delete_period">Löschen</button> --}}
        </div>
        <div class="{{-- clear_period_data --}} col-md-12 mb-4 mb-md-5 {{-- pb-4 pb-md-5 --}}">
            <button type="button" class="btn blk_bor_btn pull-left" id="clear_period_btn">Löschen</button>
            <button type="button" class="btn orange_background_btn pull-right" id="save_period_btn">Speichern</button>
        </div>
    </div>
    <div class="row unavailability_div">
        <div class="clear_period_data col-md-12 mb-4 mb-md-5 pb-4 pb-md-5">
            {{-- <button type="button" class="btn blk_bor_btn pull-left">Alle Termine Iöschen</button> --}}
        </div>
        <div class="col-md-12 select_avail mb-4">
            <h6>Abwesenheit eintragen</h6>
        </div>
        <div class="select_coach_avail">
            <p class="col-md-12 mb-2">Mit deiner Abwesenheit löschst du alle Verfügbarkeiten im gewählten Zeitraum. Zusätzlich Sind in dieser Zeit keine Terminanfragen an dich zulässig.</p>
        </div>
        <div class="avail_from_to mb-4 col-md-12">
            <p class="period_range_val mt-3">
                <input type="text" id="from_date" {{-- placeholder="20 Mai 2019" --}}>
            </p>
            <p class="mt-3">bis</p>
            <p class="period_range_val mt-3">
                <input type="text" id="to_date" {{-- placeholder="24 Mai 2019" --}}>
            </p>
        </div>
        {{-- <div class="furthur_period_btn col-md-12 mb-4 mb-md-5">
            <button type="button" class="btn blk_bor_btn mr-3 add_period">+ Weiterer Zeitraum</button>
            <button type="button" class="btn orange_background_btn delete_period">Löschen</button>
        </div> --}}
        <div class="{{-- clear_period_data --}} col-md-12 mb-4 mb-md-5 {{-- pb-4 pb-md-5 --}}">
            <button type="button" class="btn blk_bor_btn pull-left" id="resetUnavailabilityBtn">Löschen</button>
            <button type="button" class="btn orange_background_btn pull-right" id="saveUnavilabilityBtn">Speichern</button>
        </div>
        <div class="col-md-12">
            <table id="unavailability_table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-nowrap">Von</th>
                        <th class="text-nowrap">Bis</th>
                        <th class="text-nowrap">Bearbeiten</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>