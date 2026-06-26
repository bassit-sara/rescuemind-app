<?php
$file = 'resources/views/super-admin/mt3/home-recovery.blade.php';
$content = file_get_contents($file);

$replacement = <<<'EOF'
<div x-data="{ 
    showModal: false, 
    modalMode: 'add', 
    currentItem: { id: null, name: '', phone: '', details: '', province: '', status: 'รอตรวจสอบ' },
    items: [],
    init() {
        // Load data from PHP
        let serverData = @json($recoveries);
        this.items = serverData.map(r => ({
            raw_id: r.id,
            id: '#HR-' + String(r.id).padStart(3, '0'),
            name: r.user ? r.user.name : 'Unknown',
            phone: r.user ? r.user.phone : '-',
            details: r.additional_details || '-',
            province: r.address || '-',
            status: r.status === 'pending' ? 'รอตรวจสอบ' : (r.status === 'completed' ? 'เสร็จสิ้น' : 'กำลังซ่อมแซม')
        }));

        // Listen for new requests via WebSocket
        if (typeof window.Echo !== 'undefined') {
            window.Echo.channel('mt3.home-recovery')
                .listen('HomeRecoveryRequested', (e) => {
                    let r = e.recovery;
                    this.items.unshift({
                        raw_id: r.id,
                        id: '#HR-' + String(r.id).padStart(3, '0'),
                        name: r.user ? r.user.name : 'Unknown',
                        phone: r.user ? r.user.phone : '-',
                        details: r.additional_details || '-',
                        province: r.address || '-',
                        status: r.status === 'pending' ? 'รอตรวจสอบ' : (r.status === 'completed' ? 'เสร็จสิ้น' : 'กำลังซ่อมแซม')
                    });
                    
                    // Show a toast notification
                    if(typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: 'มีคำขอฟื้นฟูบ้านใหม่เข้าสู่ระบบ',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
        }
    },
EOF;

$pattern = '/<div x-data=\"\{.*?init\(\)\s*\{.*?\}(?=\s*,\s*saveItem\(\)\s*\{)/s';
$content = preg_replace($pattern, $replacement, $content);

file_put_contents($file, $content);
