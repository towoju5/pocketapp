<div class="p-4 h-full" style="background: #151726">
  <h2 class="text-lg font-semibold">Pending Trades</h2>
  <div class="flex items-center justify-between gap-2 my-3">
    <button
      onclick="switchTab('bythetime', this)"
      class="tab-btn p-2 w-full bg-gray-800 hover:bg-gray-700 rounded-md text-xs"
    >
      By the time
    </button>

    <button
      onclick="switchTab('bytheprice', this)"
      class="tab-btn p-2 w-full bg-gray-800 hover:bg-gray-700 rounded-md text-xs"
    >
      By the Price
    </button>

    <button
      onclick="switchTab('current', this)"
      class="tab-btn p-2 w-full bg-gray-800 hover:bg-gray-700 rounded-md text-xs"
    >
      Current
    </button>
  </div>

  <div id="bythetime" class="content mt-3 flex flex-col gap-3">
    <p class="text-sm">Make a trade at the specific time</p>
    <div>
      <label class="block text-gray-400 mb-1 text-xs">Asset</label>
      <select
        style="background: #162032; border: 1px solid #44506a"
        class="w-full p-2 font-light rounded-sm text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
      >
        <option>American Exprress OTC</option>
        <option>American Exprress OTC</option>
      </select>
    </div>

    <div>
      <label class="block text-gray-400 mb-1 text-xs">Open time</label>
      <div
        style="border: 1px solid #44506a"
        class="flex items-center justify-between"
      >
        <div
          style="background: #232f44"
          class="h-10 w-10 p-2 flex items-center justify-center"
        >
          <i class="fa fa-clock-o"></i>
        </div>

        <input
          style="background: #162032"
          type="datetime-local"
          class="w-full p-2 text-sm rounded-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
          value="2025-02-11T16:40:19"
        />
      </div>
      <p class="text-gray-500 text-xs mt-1">
        Current time: 2025-02-11 16:38:58
      </p>
    </div>

    <div>
      <label class="block text-gray-400 mb-1 text-sm">Payout</label>
      <div
        style="border: 1px solid #44506a"
        class="flex items-center justify-between"
      >
        <span style="background: #232f44" class="p-2 text-sm">From</span>
        <input
          style="background: #151726"
          type="number"
          class="w-full p-2 rounded-sm text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 pr-8"
          value="60"
          min="0"
          max="100"
        />
        <span
          style="background: #232f44"
          class="h-10 w-10 p-2 text-sm flex items-center justify-center"
          >%</span
        >
      </div>
    </div>

    <div>
      <label class="block text-gray-400 mb-1 text-sm">Timeframe</label>

      <div>
        <div class="flex items-center justi text-sm ween gap-5">
          <button class="w-1/3 rounded-lg set_time_plus">+</button>
          <button class="w-1/3 rounded-lg set_time_plus">+</button>
          <button class="w-1/3 rounded-lg set_time_plus">+</button>
        </div>

        <div class="text-white flex items-center justify-between">
          <div class="set_time_time" id="timeInput">01</div>
          :
          <div class="set_time_time">12</div>
          :
          <div class="set_time_time">33</div>
        </div>

        <div class="flex items-center justify-between gap-5">
          <button class="w-1/3 rounded-lg set_time_plus">-</button>
          <button class="w-1/3 rounded-lg set_time_plus">-</button>
          <button class="w-1/3 rounded-lg set_time_plus">-</button>
        </div>
      </div>
    </div>

    <div>
      <label class="block text-gray-400 mb-1 text-sm">Amount</label>
      <div
        style="border: 1px solid #44506a"
        class="flex items-center justify-between"
      >
        <input
          style="background: #151726"
          type="number"
          class="w-full p-2 rounded-sm text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 pr-8"
          value="1,500"
          min="0"
          max="100"
        />
        <span
          style="background: #232f44"
          class="h-10 w-10 p-2 text-sm flex items-center justify-center"
          >#</span
        >
      </div>
    </div>

    <div class="flex space-x-4">
      <button
        class="bg-green-600 text-white py-2 flex-1 rounded-lg flex items-center justify-center space-x-2"
      >
        <svg
          width="24px"
          height="24px"
          viewBox="0 0 24 24"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
          <g
            id="SVGRepo_tracerCarrier"
            stroke-linecap="round"
            stroke-linejoin="round"
          ></g>
          <g id="SVGRepo_iconCarrier">
            <rect width="24" height="24" fill="none"></rect>
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2ZM16 14C16 14.5523 15.5523 15 15 15C14.4477 15 14 14.5523 14 14V11.4142L9.70711 15.7071C9.31658 16.0976 8.68342 16.0976 8.29289 15.7071C7.90237 15.3166 7.90237 14.6834 8.29289 14.2929L12.5858 10H10C9.44772 10 9 9.55228 9 9C9 8.44772 9.44772 8 10 8H14.6717C15.4054 8 16 8.59489 16 9.32837V14Z"
              fill="#015b17"
            ></path>
          </g>
        </svg>
        <span>BUY</span>
      </button>
      <button
        class="bg-red-600 text-white py-2 flex-1 rounded-lg flex items-center justify-center space-x-2"
      >
        <svg
          width="24px"
          height="24px"
          viewBox="0 0 24.00 24.00"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
          <g
            id="SVGRepo_tracerCarrier"
            stroke-linecap="round"
            stroke-linejoin="round"
          ></g>
          <g id="SVGRepo_iconCarrier">
            <rect width="24" height="24" fill="none"></rect>
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2ZM9.70711 8.29289C9.31658 7.90237 8.68342 7.90237 8.29289 8.29289C7.90237 8.68342 7.90237 9.31658 8.29289 9.70711L12.5858 14H10C9.44772 14 9 14.4477 9 15C9 15.5523 9.44772 16 10 16H14.6717C15.4054 16 16 15.4051 16 14.6716V10C16 9.44772 15.5523 9 15 9C14.4477 9 14 9.44772 14 10V12.5858L9.70711 8.29289Z"
              fill="#990000"
            ></path>
          </g>
        </svg>
        <span>SELL</span>
      </button>
    </div>
    <p class="text-xs text-white">
      You should have enough balance at the time of making a trade. <br />
      The actual payout rate at the moment of making atrade will be applied
    </p>
  </div>

  <!-- //////////////////////////// -->
  <div id="bytheprice" class="content hidden mt-3 flex flex-col gap-3">
    <p class="text-sm">Make a trade at the specific asset price</p>
    <div>
      <label class="block text-gray-400 mb-1 text-xs">Asset</label>
      <select
        style="background: #162032; border: 1px solid #44506a"
        class="w-full p-2 font-light rounded-sm text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
      >
        <option>American Exprress OTC</option>
        <option>American Exprress OTC</option>
      </select>
    </div>

    <div>
      <label class="block text-gray-400 mb-1 text-xs">Open Price</label>
      <div
        style="border: 1px solid #44506a"
        class="flex items-center justify-between"
      >
        <div
          style="background: #232f44"
          class="h-10 w-10 p-2 flex items-center justify-center"
        >
          <i class="fa fa-line-chart"></i>
        </div>

        <input
          style="background: #162032"
          type="num"
          class="w-full p-2 text-sm rounded-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
          value="173.981"
        />
      </div>
      <p class="text-gray-500 text-xs mt-1">Current price: 123.45</p>
    </div>

    <div>
      <label class="block text-gray-400 mb-1 text-sm">Payout</label>
      <div
        style="border: 1px solid #44506a"
        class="flex items-center justify-between"
      >
        <span style="background: #232f44" class="p-2 text-sm">From</span>
        <input
          style="background: #151726"
          type="number"
          class="w-full p-2 rounded-sm text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 pr-8"
          value="60"
          min="0"
          max="100"
        />
        <span
          style="background: #232f44"
          class="h-10 w-10 p-2 text-sm flex items-center justify-center"
          >%</span
        >
      </div>
    </div>

    <div>
      <label class="block text-gray-400 mb-1 text-sm">Timeframe</label>

      <div>
        <div class="flex items-center justi text-sm ween gap-5">
          <button class="w-1/3 rounded-lg set_time_plus">+</button>
          <button class="w-1/3 rounded-lg set_time_plus">+</button>
          <button class="w-1/3 rounded-lg set_time_plus">+</button>
        </div>

        <div class="text-white flex items-center justify-between">
          <div class="set_time_time" id="timeInput">01</div>
          :
          <div class="set_time_time">12</div>
          :
          <div class="set_time_time">33</div>
        </div>

        <div class="flex items-center justify-between gap-5">
          <button class="w-1/3 rounded-lg set_time_plus">-</button>
          <button class="w-1/3 rounded-lg set_time_plus">-</button>
          <button class="w-1/3 rounded-lg set_time_plus">-</button>
        </div>
      </div>
    </div>

    <div>
      <label class="block text-gray-400 mb-1 text-sm">Amount</label>
      <div
        style="border: 1px solid #44506a"
        class="flex items-center justify-between"
      >
        <input
          style="background: #151726"
          type="number"
          class="w-full p-2 rounded-sm text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 pr-8"
          value="1,500"
          min="0"
          max="100"
        />
        <span
          style="background: #232f44"
          class="h-10 w-10 p-2 text-sm flex items-center justify-center"
          >#</span
        >
      </div>
    </div>

    <div class="flex space-x-4">
      <button
        class="bg-green-600 text-white py-2 flex-1 rounded-lg flex items-center justify-center space-x-2"
      >
        <svg
          width="24px"
          height="24px"
          viewBox="0 0 24 24"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
          <g
            id="SVGRepo_tracerCarrier"
            stroke-linecap="round"
            stroke-linejoin="round"
          ></g>
          <g id="SVGRepo_iconCarrier">
            <rect width="24" height="24" fill="none"></rect>
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2ZM16 14C16 14.5523 15.5523 15 15 15C14.4477 15 14 14.5523 14 14V11.4142L9.70711 15.7071C9.31658 16.0976 8.68342 16.0976 8.29289 15.7071C7.90237 15.3166 7.90237 14.6834 8.29289 14.2929L12.5858 10H10C9.44772 10 9 9.55228 9 9C9 8.44772 9.44772 8 10 8H14.6717C15.4054 8 16 8.59489 16 9.32837V14Z"
              fill="#015b17"
            ></path>
          </g>
        </svg>
        <span>BUY</span>
      </button>
      <button
        class="bg-red-600 text-white py-2 flex-1 rounded-lg flex items-center justify-center space-x-2"
      >
        <svg
          width="24px"
          height="24px"
          viewBox="0 0 24.00 24.00"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
          <g
            id="SVGRepo_tracerCarrier"
            stroke-linecap="round"
            stroke-linejoin="round"
          ></g>
          <g id="SVGRepo_iconCarrier">
            <rect width="24" height="24" fill="none"></rect>
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2ZM9.70711 8.29289C9.31658 7.90237 8.68342 7.90237 8.29289 8.29289C7.90237 8.68342 7.90237 9.31658 8.29289 9.70711L12.5858 14H10C9.44772 14 9 14.4477 9 15C9 15.5523 9.44772 16 10 16H14.6717C15.4054 16 16 15.4051 16 14.6716V10C16 9.44772 15.5523 9 15 9C14.4477 9 14 9.44772 14 10V12.5858L9.70711 8.29289Z"
              fill="#990000"
            ></path>
          </g>
        </svg>
        <span>SELL</span>
      </button>
    </div>
    <p class="text-xs text-white">
      You should have enough balance at the time of making a trade. <br />
      The actual payout rate at the moment of making atrade will be applied
    </p>

    <!-- /// -->

    <!-- Lorem ipsum dolor, sit amet consectetur adipisicing elit. Veritatis maxime
    eos similique laudantium molestias praesentium rem doloribus consequatur
    labore numquam, voluptatem explicabo quis dignissimos porro! Molestias
    dignissimos rem esse recusandae! Atque illo explicabo blanditiis molestiae,
    corporis asperiores a officiis dicta labore tenetur saepe nesciunt?
    Reprehenderit sint quaerat quasi excepturi praesentium iste veritatis
    obcaecati quas fugiat, ad facere atque necessitatibus quis veniam temporibus
    recusandae suscipit unde a omnis iure tempore. Quo earum aspernatur quae,
    sed modi eum neque tempora. Dolor architecto nisi perspiciatis assumenda
    corporis eum explicabo consequuntur molestiae, adipisci, qui provident, eos
    soluta. Esse doloribus omnis quisquam error ipsum corporis, ullam animi
    dolorem saepe quas? Quibusdam obcaecati nisi incidunt quis magnam dolorum
    provident deleniti necessitatibus, architecto hic ea praesentium eius cum
    modi maxime quia voluptates in voluptate dolores autem ratione sapiente
    nobis a. Quas molestiae esse dolorem corporis odio ipsum at quidem nisi
    blanditiis! Magnam nihil consequatur saepe officia unde vitae eaque ullam
    libero maxime tempore accusantium fugit quidem quo numquam, quod voluptatem
    corrupti et error totam ex ab quaerat modi eius? Cupiditate error officiis,
    quam dolorem explicabo blanditiis fuga expedita ducimus, a, ratione porro
    excepturi quisquam delectus autem similique rerum assumenda repellat tempora
    animi ullam doloremque mollitia culpa suscipit? Ipsa exercitationem fuga,
    facere cupiditate eveniet reprehenderit inventore repellat quidem, optio,
    labore ut quasi! Facilis eaque ullam rem impedit labore! Inventore quis ipsa
    aliquid quod nam similique voluptatum facilis. Quidem quam praesentium
    magni! Laboriosam quam cupiditate repudiandae, deserunt laborum incidunt
    cum! Placeat, unde? Debitis quasi ipsa rerum reiciendis ducimus nemo
    veritatis sunt deleniti? Sequi odit et quo accusamus beatae perferendis
    explicabo assumenda tenetur! Necessitatibus, dolores cupiditate! Ipsa unde
    non harum officiis odit quos! Earum laborum fugit iure velit impedit fugiat,
    iusto autem natus, quaerat alias quae atque inventore in vitae delectus
    aspernatur voluptatum omnis illo maiores ad dolorum doloribus hic modi quia!
    Molestias voluptate amet dolore? Quas impedit sunt voluptatem temporibus
    aperiam provident repellendus cumque quidem magni, suscipit error sed quasi
    dicta doloribus nulla distinctio molestiae asperiores itaque. Nihil labore
    et necessitatibus, explicabo quos perspiciatis, eligendi magnam repellendus
    nisi minima aliquid pariatur. Ea officiis possimus quam commodi veritatis
    est dolorum fugit quas earum, totam pariatur quaerat voluptatibus quos?
    Nobis, dolorum amet molestias voluptas soluta sint ipsum quasi dolorem
    magnam quos, est, dolores et unde error! Porro quasi eius laboriosam natus
    optio repellendus iste deserunt dolor quas facere, expedita beatae
    necessitatibus ipsum fuga rem minima dolorem, amet quaerat repellat magnam
    illum voluptatum! Molestiae in, voluptatum dolor quos unde earum debitis
    minima distinctio blanditiis repellat, accusantium animi qui repellendus
    nesciunt aspernatur dolorum ut dignissimos nisi architecto inventore dolores
    quas odit non harum? Ullam officia exercitationem debitis fugiat, eum, dicta
    incidunt architecto enim minima odit dolorem possimus magni, eveniet soluta
    labore distinctio. Cumque aliquam earum beatae ducimus doloribus architecto
    odio minima molestiae quod perspiciatis repudiandae, vel ex fuga dignissimos
    voluptatem. Quidem eaque ipsa, dolorum pariatur blanditiis, itaque quos
    ratione perferendis possimus, ut exercitationem optio at asperiores! A,
    iusto praesentium fugit, soluta nulla fugiat eaque quo alias autem error
    consequatur corrupti illo eligendi ipsam sint provident unde aperiam, totam
    quis! Cumque sunt, cupiditate quisquam ipsa molestiae quibusdam maxime! Rem
    consequuntur nisi reprehenderit, dolor quos vitae dolore corrupti esse,
    perferendis magni ullam maxime ipsa harum obcaecati. Error odit voluptas
    distinctio, quo enim, autem, fugit molestiae doloribus hic cum atque ipsam
    officia quis cumque est! Quae, mollitia ea! Error odit, assumenda et quidem
    odio soluta ex nisi quasi eligendi cupiditate temporibus voluptates hic
    deleniti repellendus magnam voluptate laboriosam sunt dignissimos unde
    quibusdam architecto accusamus tempora cumque consectetur? Ipsum facilis
    possimus non pariatur dolorum vel odio dolorem ullam, nisi rem praesentium.
    Quisquam suscipit nam magnam repudiandae iusto corporis nemo nobis eveniet
    dolor neque similique repellat vitae laborum commodi eum, ratione nihil
    tempore explicabo eaque nulla exercitationem facilis aut minima labore.
    Distinctio aliquid odio et temporibus inventore alias sint illo possimus
    placeat perspiciatis beatae consectetur suscipit reprehenderit, fugit
    obcaecati non, aliquam maiores ipsa accusamus a sunt hic ullam voluptatem.
    Hic quas architecto enim nihil incidunt. Eos aliquam cumque distinctio, iure
    doloribus necessitatibus aspernatur, quisquam nostrum, accusamus numquam
    illo corporis magnam beatae quos id sint deleniti ducimus fugiat eaque dicta
    repudiandae! Eveniet excepturi hic, enim dicta odio quos dolores iure
    debitis porro commodi, recusandae magni similique deleniti molestiae
    quisquam! Ad et porro excepturi pariatur dolor tempora reprehenderit
    nostrum, animi ipsam hic ab sunt exercitationem suscipit eius molestias!
    Officiis expedita in reprehenderit temporibus, eaque magni quos commodi
    perferendis totam, laudantium consequuntur non quo qui ipsa alias
    dignissimos repellendus iste omnis officia at! Quidem mollitia neque
    officiis numquam consectetur, maiores dignissimos nesciunt culpa doloremque
    voluptatibus ab, ullam eveniet repudiandae qui, ipsa architecto delectus
    pariatur quaerat sunt vel vitae distinctio nulla odit. Quod voluptatum totam
    numquam excepturi tenetur eum, magni deleniti porro doloribus doloremque,
    exercitationem consequatur ab adipisci libero, est illo aut. Cupiditate enim
    placeat minima nemo quas est alias velit harum animi laboriosam. Totam
    necessitatibus quaerat eum laudantium commodi reiciendis pariatur culpa rem
    obcaecati harum vero expedita fugiat ipsum, amet eius quod reprehenderit
    illo debitis saepe voluptatibus quae dolore delectus aperiam placeat. Dolore
    enim ex, necessitatibus molestias eaque voluptates, eligendi labore
    distinctio quam rem, explicabo debitis facilis ipsam. Totam unde molestiae
    porro. Ducimus ut cum nobis odio! Inventore mollitia facere necessitatibus
    dolor deserunt. Alias quas molestias error odit perspiciatis, quibusdam
    minus dignissimos qui omnis rerum voluptate vero in ipsa obcaecati
    laudantium culpa, consequuntur veniam cumque expedita ut animi ad totam
    dolore ex? Optio ipsum dolorum quae! Atque vitae aliquid corrupti veniam
    tempore illo nostrum, adipisci placeat. Cumque sed corrupti temporibus
    nostrum itaque iste quam doloremque ab, consequatur quis magnam quidem eaque
    rem saepe et provident reprehenderit nisi iure quod velit consectetur
    veritatis culpa ducimus omnis. Tenetur vitae ipsam doloribus officiis omnis
    obcaecati voluptatibus ad quasi earum excepturi, temporibus necessitatibus.
    Quia natus incidunt, recusandae ex possimus quo culpa velit? Reiciendis
    deleniti, ratione commodi sit dignissimos dolor at corporis illum modi
    possimus repellendus autem rerum voluptatum magni nisi natus? Exercitationem
    vitae quia blanditiis, perferendis eius illo reiciendis minima culpa vel
    odio consectetur nesciunt doloremque eligendi atque illum, reprehenderit
    alias asperiores veniam harum ipsum. -->
  </div>

  <div id="current" class="content hidden p-2">
    <h2 class="text-sm font-light text-sm text-white mb-2">
      All postponed trades
    </h2>
    <div
      style="background: #394a744d; border: 1px dashed #394a744d"
      class="text-white p-2 text-sm rounded-lg border border-gray-600 text-center"
    >
      There are no pending trades
    </div>

    <!-- <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eaque distinctio mollitia laboriosam culpa blanditiis placeat officia odio porro sapiente maxime sequi, maiores aliquam impedit similique a assumenda. Nesciunt corrupti praesentium quasi necessitatibus molestias nobis cum voluptatum, distinctio optio dicta in, perspiciatis, corporis impedit. Quisquam, fuga culpa, nulla doloribus id tenetur odio voluptate suscipit architecto provident, ut exercitationem? Eos voluptatibus inventore id, repellat sunt ratione illum assumenda mollitia ducimus repellendus laborum tempore delectus atque ad. Ab sunt inventore sed autem veritatis repudiandae obcaecati laboriosam consequuntur ad, explicabo distinctio unde quis accusantium iusto iste aliquid quidem repellat impedit? Magni quibusdam accusamus repellendus, tempora facere nesciunt maxime voluptate adipisci quo at porro unde ut temporibus voluptatem, eius dicta ea vitae exercitationem nihil aliquid suscipit veritatis quia nam eaque? At iure quia soluta nam eum quisquam, omnis saepe hic id delectus aperiam culpa deserunt repellendus dicta laboriosam impedit iusto, maiores provident reprehenderit molestias recusandae animi similique modi neque! In rem doloremque eos porro voluptatibus, dicta voluptate adipisci omnis, quibusdam recusandae facilis beatae hic similique, est quaerat harum explicabo enim illo. Natus officiis blanditiis pariatur modi quae iste, eius sit nihil dolores illo repellendus eligendi laborum laboriosam tenetur quia earum eveniet qui libero fuga provident quo eum! Repellendus molestiae quam doloribus maxime nam consectetur eaque laborum rerum. Maxime molestias ullam sapiente earum repellat quo impedit sed odio fuga quae alias hic sint numquam, sequi expedita nulla aspernatur atque nesciunt soluta, ex iure dolore laboriosam. Veritatis est perspiciatis aut voluptas! Hic rem porro est unde vel, quasi deleniti repellendus accusamus fugit tempora nobis iste! Explicabo illum consectetur, aliquam in sequi consequuntur perspiciatis officiis fugiat dolores. Laborum facilis consequatur reprehenderit non ut ea voluptatum unde quisquam sunt ducimus, eos atque repellendus libero debitis tempora facere fugit error blanditiis nihil quod deleniti perferendis. Eos quasi consequuntur libero ab qui unde, accusantium blanditiis dolorem. Quasi officia corrupti delectus tempore sed dolorum, deleniti suscipit debitis quae ipsam possimus placeat aliquam, repellendus, labore odit sit commodi laudantium illum similique optio omnis doloremque nesciunt adipisci atque? Voluptate beatae, harum neque assumenda officia quidem sed qui debitis placeat dolores commodi earum possimus at suscipit quasi reiciendis odio impedit distinctio sit ea incidunt! Possimus, animi dolorem! Dolorem iusto debitis aliquid quaerat, tenetur facere. Sunt voluptas esse sit illum. Ratione fugiat minima, ab voluptatem possimus aliquam quisquam soluta ea illum rem voluptates molestiae rerum dolorum, eius exercitationem eligendi blanditiis dolor, assumenda sequi quaerat praesentium voluptatibus. Odit, maiores consequuntur. Sunt obcaecati vero vitae dolorum expedita. Rerum dolor excepturi porro perspiciatis necessitatibus nihil sint eum voluptate sit quis! Assumenda, beatae consequatur. Cupiditate, quisquam, maxime deleniti fugit consectetur, et velit officia perspiciatis vitae quia quae? In necessitatibus pariatur aperiam perferendis mollitia, quia accusamus quaerat asperiores praesentium possimus minima ea. Enim optio veniam corporis minima laborum suscipit inventore animi dolor numquam eum doloribus, vel excepturi. Beatae, voluptas. Hic tempora ipsam ex explicabo. Aspernatur, consequatur dolore assumenda odio quos necessitatibus. Eius quos commodi nostrum repudiandae iure aliquid, mollitia nam suscipit, nemo possimus earum velit dignissimos cupiditate nesciunt id porro libero illum quaerat eligendi alias ad maiores rerum nulla. Quas nesciunt totam eius? Nobis rerum atque temporibus, molestias laborum dolores, dolore quod provident numquam facere nostrum sit vitae aliquam quaerat consequuntur laboriosam ex placeat quo labore velit explicabo! Fuga molestias, omnis ducimus nisi dignissimos velit, incidunt ratione repudiandae distinctio ut perferendis, ipsa quaerat expedita? Culpa nulla accusamus nobis voluptas animi suscipit mollitia obcaecati ab ad! Delectus quas animi porro cupiditate repudiandae recusandae ducimus vel cum ex pariatur fuga dicta amet repellat accusantium voluptate eos, veritatis provident quibusdam! Beatae earum distinctio reiciendis atque molestiae doloremque doloribus quasi, unde deserunt aspernatur veritatis, est dolor suscipit sunt recusandae aut velit porro ad reprehenderit facilis pariatur! Totam fugit, debitis molestiae fuga nobis quisquam reprehenderit tenetur. Earum, temporibus consectetur ratione libero debitis odio enim rem aperiam veniam assumenda, provident deleniti sit animi cupiditate qui dignissimos rerum pariatur! Quidem eum esse reprehenderit quibusdam ullam non quis, placeat enim eius pariatur, alias quia exercitationem dolore earum in dolores. Expedita at nostrum autem consequatur blanditiis. Culpa natus dicta autem quaerat expedita! Minus, ab nostrum! Deserunt laborum inventore temporibus illum, nobis quam minima reprehenderit accusantium quia ut magnam rerum est cum, blanditiis necessitatibus sunt mollitia quae quo, odio optio? Impedit sit, amet necessitatibus quidem corrupti culpa quam a omnis veniam? Dignissimos perferendis repellendus ab praesentium dolor magni recusandae eaque ex ratione, sed totam reprehenderit? Vel placeat, ea eligendi odit illum praesentium hic sequi architecto obcaecati ipsum assumenda itaque iste quod dolores! Ut amet ullam aut odit culpa vitae libero, facere fugit veritatis praesentium neque labore quasi maxime eligendi modi? Provident suscipit impedit perferendis laudantium dolorem optio ex, vitae sint saepe est necessitatibus quas, commodi in explicabo quisquam magni id aliquam iure numquam rerum ducimus magnam maiores tempore. Itaque quis molestias magnam officia vitae, nesciunt repellat nam suscipit debitis veritatis consequatur temporibus dolores quaerat non. Sapiente quibusdam aperiam sequi voluptatem harum magni, ipsum blanditiis voluptas, culpa voluptate quia aut? Similique molestiae fugiat dolores ut voluptas neque provident itaque ea? Impedit, sit voluptatem debitis fugit distinctio facilis repudiandae tenetur vero culpa dolorem ullam ipsum maxime alias iusto quis qui inventore nam dicta id dolore recusandae aspernatur. Debitis aliquid eum, consequatur molestias fuga quis dolorem impedit et voluptatum voluptas, eos quos omnis optio, excepturi illo corporis earum natus ex vitae qui. Expedita fuga illum, obcaecati ducimus vero hic recusandae qui! Ipsa, quia autem perferendis tenetur quos atque, exercitationem aspernatur, beatae nesciunt nihil maxime. Ea, fugit ratione. Blanditiis iusto quos doloremque natus. Nemo vero aliquam, at quas magnam provident doloremque sunt, incidunt, eum ab laborum pariatur autem vitae facere reiciendis obcaecati architecto! Sunt aut maiores, optio minima quod corporis dolorum dolores dolor nemo iste, eaque accusantium reiciendis eum commodi rem vel hic expedita blanditiis temporibus at doloremque vero necessitatibus ratione. Tempore natus assumenda officiis sequi corrupti, earum, dolorum commodi ipsa impedit, doloremque a quaerat dolore? Amet voluptates consectetur iure explicabo ad tenetur et atque esse maxime tempore qui, ipsam, nulla doloribus earum ex aliquid eaque nostrum nobis itaque nesciunt fugiat saepe quam, temporibus autem.</p> -->
  </div>
</div>
