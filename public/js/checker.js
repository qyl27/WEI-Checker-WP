function check(forceClose) {
    if (navigator.getEnvironmentIntegrity instanceof Function) {
        let result = confirm("You browser has WEI support, please replace your browser with Firefox. Click confirm to download Firefox.");
        if (result) {
            window.location = 'https://www.mozilla.org/zh-CN/firefox/new/';
        } else {
            if (forceClose) {
                try {
                    window.location.href = "about:blank";
                    window.opener = null;
                    window.open('', '_self');
                    window.close();
                } catch (e) {
                }
            }
        }
    }
}
