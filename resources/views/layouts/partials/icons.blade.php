{{-- Set de iconos SVG (Heroicons outline, inline para no depender de paquetes extra) --}}
@switch($icon)
    @case('home')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h4a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h4a1 1 0 001-1V10" /></svg>
        @break

@case('user')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
        <circle cx="12" cy="7" r="4" stroke-width="2" />
    </svg>
    @break

    @case('shield')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5-1a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        @break
    @case('tag')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5.586a1 1 0 01.707.293l7.414 7.414a1 1 0 010 1.414l-7.586 7.586a1 1 0 01-1.414 0L4.293 12.293A1 1 0 014 11.586V6a3 3 0 013-3z" /></svg>
        @break
    @case('box')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
        @break
    @case('chart')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
        @break

    @case('cog')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 10.27 7 3.34" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m11 13.73-4 6.93" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22v-2" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 12h8" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m17 20.66-1-1.73" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m17 3.34-1 1.73" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12h2" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m20.66 17-1.73-1" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m20.66 7-1.73 1" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3.34 17 1.73-1" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3.34 7 1.73 1" />
        <circle cx="12" cy="12" r="2" stroke-width="2" />
        <circle cx="12" cy="12" r="8" stroke-width="2" />
    </svg>
    @break

    @case('search')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" /></svg>
        @break
    @case('bell')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
        @break
    @case('menu')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        @break
    @case('chevron-down')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
        @break
    @case('eye')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
        @break
    @case('pencil')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
        @break
    @case('trash')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 4h5a1 1 0 011 1v2h-7V5a1 1 0 011-1z" /></svg>
        @break
    @case('clipboard')
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6m-5 4h4m-6 4h8m-8 4h5M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
        </svg>
        @break
    @case('motorcycle')
        <svg viewBox="0 0 256 256" fill="currentColor" class="w-full h-full scale-110">
            <path d="M216 120a41 41 0 0 0-6.6.55l-5.82-15.14A55.6 55.6 0 0 1 216 104a8 8 0 0 0 0-16h-19.12l-13.41-34.87A8 8 0 0 0 176 48h-32a8 8 0 0 0 0 16h26.51l9.23 24H152c-18.5 0-33.5 4.31-43.37 12.46a16 16 0 0 1-16.76 2.07c-10.58-4.81-73.29-30.12-73.8-30.26a8 8 0 0 0-5 15.19s55.5 21.94 66.53 32.94A55.67 55.67 0 0 1 95.43 152H79.2a40 40 0 1 0 0 16h52.12a31.91 31.91 0 0 0 30.74-23.1a56 56 0 0 1 26.59-33.72l5.82 15.13A40 40 0 1 0 216 120M40 168h22.62a24 24 0 1 1 0-16H40a8 8 0 0 0 0 16m176 16a24 24 0 0 1-15.58-42.23l8.11 21.1a8 8 0 1 0 14.94-5.74L215.35 136h.65a24 24 0 0 1 0 48" />
        </svg>
         @break

    @case('list-check')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 5H3" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H3" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19H3" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18 2 2 4-4" />
    </svg>
        @break

    @case('square-mouse-pointer')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.034 12.681a.498.498 0 0 1 .647-.647l9 3.5a.5.5 0 0 1-.033.943l-3.444 1.068a1 1 0 0 0-.66.66l-1.067 3.443a.5.5 0 0 1-.943.033z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 11V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6" />
    </svg>
    @break

    @case('alarm-clock')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full">
        <circle cx="12" cy="13" r="8" stroke-width="2" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4l2 2" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3 2 6" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m22 6-3-3" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.38 18.7 4 21" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.64 18.67 20 21" />
    </svg>
    @break

    @case('file-cog')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.85 22H18a2 2 0 0 0 2-2V8a2 2 0 0 0-.586-1.414l-4-4A2 2 0 0 0 14 2H6a2 2 0 0 0-2 2v6.6" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v5a1 1 0 0 0 1 1h5" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3.305 19.53.923-.382" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4.228 16.852-.924-.383" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5.852 15.228-.383-.923" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5.852 20.772-.383.924" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.148 15.228.383-.923" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.53 21.696-.382-.924" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9.773 16.852.922-.383" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9.773 19.148.922.383" />
        <circle cx="7" cy="18" r="3" stroke-width="2" />
    </svg>
    @break


   @case('grid-2x2-check')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v17a1 1 0 0 1-1 1H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6a1 1 0 0 1-1 1H3" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16 19 2 2 4-4" />
    </svg>
    @break


    @case('shield-user')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.376 18.91a6 6 0 0 1 11.249.003" />
        <circle cx="12" cy="11" r="4" stroke-width="2" />
    </svg>
    @break

    @case('hand-coins')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m2 16 6 6" />
            <circle cx="16" cy="9" r="2.9" stroke-width="2" />
            <circle cx="6" cy="5" r="3" stroke-width="2" />
        </svg>
        @break

    @case('square-arrow-up-right')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full">
        <rect width="18" height="18" x="3" y="3" rx="2" stroke-width="2" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 8h8v8" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 16 8-8" />
    </svg>
    @break

    @case('calendar-fold')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 20a2 2 0 0 0 2 2h10a2.4 2.4 0 0 0 1.706-.706l3.588-3.588A2.4 2.4 0 0 0 21 16V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 22v-5a1 1 0 0 1 1-1h5" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 2v4" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18" />
    </svg>
    @break

    @case('user-cog')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 15H6a4 4 0 0 0-4 4v2" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.305 16.53.923-.382" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15.228 13.852-.923-.383" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.852 12.228-.383-.923" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.852 17.772-.383.924" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.148 12.228.383-.923" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.53 18.696-.382-.924" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m20.772 13.852.924-.383" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m20.772 16.148.924.383" />
        <circle cx="18" cy="15" r="3" stroke-width="2" />
        <circle cx="9" cy="7" r="4" stroke-width="2" />
    </svg>
    @break

    @case('tool-case')
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 15h4" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.817 10.995-.971-1.45 1.034-1.232a2 2 0 0 0-2.025-3.238l-1.82.364L9.91 3.885a2 2 0 0 0-3.625.748L6.141 6.55l-1.725.426a2 2 0 0 0-.19 3.756l.657.27" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m18.822 10.995 2.26-5.38a1 1 0 0 0-.557-1.318L16.954 2.9a1 1 0 0 0-1.281.533l-.924 2.122" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12.006A1 1 0 0 1 4.994 11H19a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z" />
    </svg>
    @break

    @case('activity')
    <svg viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         class="h-6 w-6">
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2"
        />
    </svg>
    @break


    @default
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><circle cx="12" cy="12" r="9" stroke-width="2" /></svg>
    @endswitch
