document.addEventListener('keydown', function (e) {
    // Bloque F12
    if (e.key === 'F12') {
        e.preventDefault();
    }

    // Bloque Ctrl+Shift+I
    if (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'i')) {
        e.preventDefault();
    }

    // Bloque Ctrl+Shift+J
    if (e.ctrlKey && e.shiftKey && (e.key === 'J' || e.key === 'j')) {
        e.preventDefault();
    }

    // Bloque Ctrl+U
    if (e.ctrlKey && (e.key === 'U' || e.key === 'u')) {
        e.preventDefault();
    }

      // Bloque Ctrl+Shift+G
      if (e.ctrlKey && e.shiftKey && (e.key === 'G' || e.key === 'G')) {
        e.preventDefault();
    }
    
});

document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
});
