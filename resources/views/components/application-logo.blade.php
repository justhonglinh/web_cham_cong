<svg viewBox="0 0 316 316" xmlns="http://www.w3.org/2000/svg" {{ $attributes ?? '' }}>

    <!-- Vòng tròn ngoài -->
    <circle cx="158" cy="158" r="150" stroke="#2C7A7B" stroke-width="8" fill="#E6FFFA" />

    <!-- Kim đồng hồ giờ -->
    <rect x="153" y="80" width="10" height="70" rx="5" ry="5" fill="#2C7A7B" />

    <!-- Kim đồng hồ phút -->
    <rect x="153" y="80" width="6" height="100" rx="3" ry="3" fill="#319795" transform="rotate(45 158 158)" />

    <!-- Trung tâm đồng hồ -->
    <circle cx="158" cy="158" r="12" fill="#2C7A7B" />

    <!-- Dấu tick (check) -->
    <path d="M110 185 L140 215 L205 150" stroke="#319795" stroke-width="15" fill="none" stroke-linecap="round" stroke-linejoin="round"/>

</svg>
