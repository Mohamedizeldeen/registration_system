// Script to add navigation to all pages
const pagesToUpdate = [
    { file: 'Events/create.html', page: 'events' },
    { file: 'Attendees/index.html', page: 'attendees' },
    { file: 'Attendees/create.html', page: 'attendees' },
    { file: 'Tickets/index.html', page: 'tickets' },
    { file: 'Coupons/index.html', page: 'coupons' },
    { file: 'Companies/show.html', page: 'companies' },
    { file: 'Companies/edit.html', page: 'companies' }
];

// This is a reference file for the manual updates needed
// Each page needs:
// 1. Replace CSS link with Tailwind CDN
// 2. Add empty line after <body> tag
// 3. Add navigation scripts before closing </body> tag

console.log('Pages to update:', pagesToUpdate);