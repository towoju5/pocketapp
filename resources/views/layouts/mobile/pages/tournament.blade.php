<div class="p-4 h-full">
  <!-- Header -->
  <div class="flex w-full h-12 justify-between space-x-4 items-center mb-6">
    <button
      onclick="switchTab('all-tournaments', this)"
      class="tab-btn text-white text-xs w-full p-2 rounded-lg"
    >
      All Tournaments
    </button>
    <button
      onclick="switchTab('tournament-statistics', this)"
      class="tab-btn text-white text-xs w-full p-2 rounded-lg"
    >
      Statistics
    </button>
  </div>

  <div id="all-tournaments" class="content flex flex-col gap-4">
    <!-- Tournament Card: Hour Play -->
    <div class="">
      <div
        style="
          background: radial-gradient(
              131.28% 421.17% at 100% 0,
              rgba(8, 124, 199, 0.2) 0,
              transparent 100%
            ),
            #1e2131;
        "
        class="rounded-t-lg px-5 pt-5 flex items-center justify-between"
      >
        <div>
          <div class="text-xl">Hour play</div>
          <div class="">
            <div class="text-xs" style="color: #8ea5c0">Prize fund</div>
            <div class="">₦150,000</div>
          </div>
          <div class="">
            <div class="text-xs" style="color: #8ea5c0">Participation fee</div>
            <div class="">₦1,500</div>
          </div>
        </div>
        <div class="tournament__img-wrap">
          <img
            src="https://m.pocketoption.com/images/cabinet/tournaments/hour_play.png"
            srcset="
              https://m.pocketoption.com/images/cabinet/tournaments/hour_play.png     1x,
              https://m.pocketoption.com/images/cabinet/tournaments/hour_play@2x.webp 2x
            "
            alt="Hour play"
            class="tournament__img"
          />
        </div>
      </div>
      <div
        style="background: #262b3d"
        class="px-5 py-2 rounded-b-lg flex items-center justify-between"
      >
        <div>
          <div class="text-xs" style="color: #8ea5c0">Ends in:</div>
          <div>00:05:29</div>
        </div>
        <button
          class="px-10 py-2 text-xs"
          style="background: #172832; border: 1px solid #025b44"
        >
          Join
        </button>
      </div>
    </div>
    <!-- Tournament Card: Day Off -->
    <div class="">
      <div
        style="
          background: radial-gradient(
              126.93% 414.63% at 98.83% 2.29%,
              rgba(136, 51, 203, 0.2) 0,
              transparent 100%
            ),
            #1e2131;
        "
        class="rounded-t-lg px-5 pt-5 flex items-center justify-between"
      >
        <div>
          <div class="text-xl">Day Off</div>
          <div class="">
            <div class="text-xs" style="color: #8ea5c0">Prize fund</div>
            <div class="">₦370,000</div>
          </div>
          <div class="">
            <div class="text-xs" style="color: #8ea5c0">Participation fee</div>
            <div class="">Free</div>
          </div>
        </div>
        <div class="tournament__img-wrap">
          <img
            src="https://m.pocketoption.com/images/cabinet/tournaments/hour_play.png"
            srcset="
              https://m.pocketoption.com/images/cabinet/tournaments/hour_play.png     1x,
              https://m.pocketoption.com/images/cabinet/tournaments/hour_play@2x.webp 2x
            "
            alt="Hour play"
            class="tournament__img"
          />
        </div>
      </div>
      <div
        style="background: #262b3d"
        class="px-5 py-2 rounded-b-lg flex items-center justify-between"
      >
        <div>
          <div class="text-xs" style="color: #8ea5c0">Ends in:</div>
          <div>00:05:29</div>
        </div>
        <button
          class="px-10 py-2 text-xs"
          style="background: #172832; border: 1px solid #025b44"
        >
          Join
        </button>
      </div>
    </div>

    <!-- Tournament Card: Hour Play -->
  </div>

  <!-- //// -->
  <div
    id="tournament-statistics"
    class="content hidden p-2 w-60"
    style="color: #8ea5c0"
  >
    <h2 class="text-sm text-white mb-2" style="color: #8ea5c0">
      Tournament Stats
    </h2>
    <div class="space-y-2 text-gray-300 text-sm" style="color: #8ea5c0">
      <p class="flex justify-between">
        <span>Tournaments won:</span> <span>0</span>
      </p>
      <p class="flex justify-between">
        <span>Total prize money:</span> <span>₦0</span>
      </p>
      <p class="flex justify-between">
        <span>Largest prize:</span> <span>₦0</span>
      </p>
    </div>
  </div>
</div>
