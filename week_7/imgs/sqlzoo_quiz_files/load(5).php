/* MediaWiki:Common.js */
//Analytics code
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-33860668-1', 'sqlzoo.net');
ga('send', 'pageview');

//Common.js original
/* Any JavaScript here will be loaded for all users on every page load. */
var numberOfQuestions = 0;
var numberOfAnswered = 0;
var numberOfCorrect = 0;

//Function to find results in Object ==> getObjects(objName, 'id', 'dataWhatYouLookingFor'); // Returns an array of matching objects
function getObjects(obj, key, val) {
  var objects = [];
  for (var i in obj) {
    if (!obj.hasOwnProperty(i)) continue;
    if (typeof obj[i] == 'object') {
      objects = objects.concat(getObjects(obj[i], key, val));
    } else if (i == key && obj[key] == val) {
      objects.push(obj);
    }
  }
  return objects;
}

$(function () {
  //Display Path in sidebar (Istvan)
  //GET COHORT
  if ($.inArray('user', wgUserGroups) >= 0) {
    $.getJSON('/userData.php', {
      action: 'userCohort',
      wgUserName: wgUserName
    }, function (d) {
      //IF the user is member of a cohort create a different menu.
      if (d.cohort.length > 0) {
        var divPathMenu = $($('<div/>', {
          id: 'divSlideCont'
        })).append($('<div/>', {
          id: 'divPathMenuCont'
        }));
        var pathMenu = $('<div class="portal"/>').append(divPathMenu);
        $('#p-Reference').before(pathMenu);

        function createPath(cohort) {
          $.getJSON('/userData.php', {
            action: 'userPath',
            cohort: cohort
          }, function (e) {
            $('#divPathMenuCont').css({
              'background': 'none'
            });
            var path = e.path[0].pathName;
            $('#divPathMenuCont').empty();
            $.getJSON('/userData.php', {
              action: 'menuDb',
              path: path,
              member: wgUserName
            }, function (f) {
              var ul = $('<ul/>', {
                id: 'cohortMenu',
                'class': 'dropdown mm_sub'
              });
              for (var i = 0; i < f.tut.length; i++) {
                var NoCObj = [];
                var NoQObj = [];
                var NoCs = 0; //NumberOfCorrect answers
                var NoQs = 0; //NumberOfQuestions in a tutorial
                NoQObj = getObjects(f.maxCor, 'wikipage', f.tut[i].wikipage);
                NoCObj = getObjects(f.statMe, 'sname', f.tut[i].sname);
                if (NoCObj.length == 0) {
                  NoCs = 0;
                } else {
                  NoCs = parseInt(NoCObj[0].correct);
                }
                NoQs = parseInt(NoQObj[0].maxCor);
                var maxWidth = 29.2; // adjust the value so that it fits menu well
                var pbWidth = parseFloat(maxWidth) * NoCs / NoQs;
                var mprogbar = $('<div/>', {
                  'class': 'progressbarbg1'
                }).append($('<div/>', {
                  'class': 'progressbar1',
                  'id': f.tut[i].wikipage,
                  css: {
                    'width': pbWidth
                  }
                }));
                ul.append($('<li/>').append($('<a/>', {
                  'class': 'pathTxta',
                  href: '/wiki/' + f.tut[i].wikipage,
                  text: f.tut[i].sname
                })).append(mprogbar));
              }
              $('#divPathMenuCont')
                .append($('<div class=body/>')
                  .append(ul)
              )
                .append($("<table id='pathMenuTable'><tr><td>Cohort: </td><td>" + cohort + "</td></tr><tr><td>Path: </td><td>" + path + "</td></tr><tr><td>Teacher: </td><td>" + e.path[0].teacher + "</td></tr></table>"))
                .append($('<div/>', {
                  'class': 'divClear'
                }));
              if (d.cohort.length > 1) {
                $('#divPathMenuCont').append($('<div/>', {
                  id: 'divBtns'
                })).append($('<div/>', {
                  'class': 'divClear'
                }));
                $('#divBtns')
                  .append('<div class="dwnBtnClass"></div>') //<img id="dwnBtn" src="/design/dwnArrow_black.png" />
                .append('<div class="upBtnClass"></div>'); //<img id="upBtn" src="/design/upArrow_black.png" />
              }
            });
          });
        }

        function getCohort(index) {
          var cohort = d.cohort[index].id;
          createPath(cohort);
        }

        var index = 0;
        getCohort(index);

        $('div.upBtnClass').live("click", function () {
          var maxIndex = d.cohort.length - 1;
          if (index >= maxIndex) {
            index = maxIndex;
          } else {
            index = index + 1;
            getCohort(index);
          }
        });

        $('div.dwnBtnClass').live("click", function () {
          var minIndex = 0;
          if (index <= minIndex) {
            index = 0;
          } else {
            index = index - 1;
            getCohort(index);
          }
        });
      }
    });
  }

  var engine = $('<div class="portal"/>')
    .append($('<div id="engineTxt"><h5>Engine</h5></div>'))
    .append($('<div id="engineSel" class="body"/>')
      .append($('<ul/>')
        .append($('<li/>')
          .append($('<select id=engine/>')
            .append('<option value=mysql>MySQL</option>')
            .append('<option value=oracle>Oracle</option>')
            .append('<option value=sqlserver>SQL Server</option>')
            .append('<option value=postgres>PostgreSQL</option>')
            .append('<option id=ingres>Ingres</option>')
            .append('<option id=db2>DB2</option>')
          )
        )
      )
  )

  $('#right-navigation').after($('<ul/>', {
      'id': 'zoolinks'
    })
    .append($('<li/>').append($('<a/>', {
      href: 'http://csszoo.net',
      text: 'CSS',
      id: 'css_link',
      title: 'Learn CSS! visit CSSzoo'
    })))
    .append($('<li/>').append($('<a/>', {
      href: 'http://progzoo.net',
      text: 'Java',
      id: 'java_link',
      title: 'Hungry for Java tutorials? ProgZoo is for you'
    })))
    .append($('<li/>').append($('<a/>', {
      href: 'http://linuxzoo.net',
      text: 'Linux',
      id: 'linux_link',
      title: 'Linux Zoo tutoials!'
    })))
    .append($('<li/>', {
      'id': 'book'
    }).append($('<a/>', {
      href: 'http://www.oreilly.com/catalog/sqlhks/',
      id: 'sql_hacks',
      title: 'Try/buy SQL Hacks book'
    })))
  );

  $('#p-Reference').before(engine);

  //Adverts
  $('<img/>', {
    src: '/design/sqlhacks.png',
    alt: 'SQL Hacks book ad'
  })
    .appendTo($('#sql_hacks'));
  $('#p-googleadsense').prependTo('#footer');

  if (wgCanonicalNamespace == 'MediaWiki' || wgCanonicalNamespace == 'Special')
    $('#p-googleadsense').hide();

  var startAt = Math.max(1, $('#startAt').text() * 1);
  var qu = $('.qu,.ht,.err');

  //Hints
  var hint = $('.hint', q);
  hint.hide();
  hint.each(function () {
    var htitle = $(this).attr("title");
    var hnt = $('<div/>', {
      'class': 'hnt',
      'text': htitle
    });
    var hidden = $(this);
    hnt.click(function (e) {
      e.preventDefault();
      hidden.toggle("slow");
    });
    $(this).before(hnt);
  });

  for (var i = 0; i < qu.length; i++) {
    var id = i + 1;
    var q = qu[i];
    var lsName = wgPageName + '_' + 'frm__' + id;
    var def = $('.def', q);
    if (def.length > 1) {
      var pick = def.filter(function () {
        var clss = $(this).attr('class').split(' ');
        for (var k = 0; k < clss.length; k++)
          if (clss[k].match("^e-")) return false;
        return true
      });
      for (var j = 0; j < def.length; j++)
        if ($(def[j]).hasClass('e-' + curEng))
          pick = $(def[j]);
      def = pick;
    }
    var txt = def.text();
    // replace the default text with user's last query if available in LS
    if (localStorage.getItem(lsName + "_arr_" + curEng)) {
      var lsArray = JSON.parse(localStorage.getItem(lsName + "_arr_" + curEng));
      txt = lsArray[lsArray.length - 1];
    }

    var ans = $('.ans', q).text();
    var nrows = 2 + Math.max(Math.max(4, txt.split(/[\n\r]+/).length), ans.split(/[\n\r]+/).length);
    var ncols = 2 + Math.max(Math.max(45, maxlen(txt.split(/[\n\r]+/))), maxlen(ans.split(/[\n\r]+/)));
    var tdy = $('.tidy', q).text();
    var frm = $('<form/>', {
      name: 'frm__' + id,
      id: 'frm__' + id
    })
      .append($('<div/>', {
          'class': 'quf'
        })
        .append($('<textarea></textarea>', {
          value: $.trim(txt),
          rows: nrows,
          cols: ncols,
          'class': 'sql',
          id: 'txtar_' + id
        }))
        .append($('<br/>'))
        .append($('<button/>', {
          text: 'Submit SQL',
          'class': 'submitSQL',
          click: goBaby
        }))
        .append($('<div/>', {
          text: 'Restore default',
          'class': 'reset',
          click: function () {
            var qu = $(this).parents('.qu, .ht, .err');
            var def = $('.def', qu);
            var txt = findBestDefText(qu, $('#engine').val());
            qu.find('textarea.sql').val(txt);
          }
        }))
    );

    def.after(frm);
    var lhs = $('<div/>', {
      css: {
        width: (ncols+10)+'ex',
        marginRight: '7ex',
        'float': 'left'
      }
    });
    lhs.append($('<span/>', {
      text: (startAt + i) + '.',
      'class': 'id'
    }));
    lhs.append($(q).children());
    $(q).append(lhs);
    $(q).append($('<div/>', {
      text: 'result',
      'class': 'res'
    }));

    //Show additional info if available for active angine
    var ecomm = $('.ecomm,.link', q);
    var ecomm1 = ecomm.filter(false);
    if (ecomm.length > 0) {
      var curEng = $('#engine').val();
      for (var j = 0; j < ecomm.length; j++)
        if ($(ecomm[j]).hasClass('e-' + curEng))
          ecomm1 = $(ecomm[j]);
    }
    for (var j = 0; j < ecomm.length; j++) {
      if ($(ecomm[j]).get(0) == ecomm1.get(0)) {
        $(ecomm[j]).show();
      } else {
        $(ecomm[j]).hide();
      }
    }

    var ecomm = $('.link', q);
    var ecomm1 = def.filter(function () {
      var clss = ($(this).attr('class')||'').split(' ');
      for (var k = 0; k < clss.length; k++)
        if (clss[k].match("^e-")) return false;
      return true
    });
    if (ecomm.length > 0) {
      var curEng = $('#engine').val();
      for (var j = 0; j < ecomm.length; j++)
        if ($(ecomm[j]).hasClass('e-' + curEng))
          ecomm1 = $(ecomm[j]);
    }
    for (var j = 0; j < ecomm.length; j++) {
      if ($(ecomm[j]).get(0) == ecomm1.get(0)) {
        $(ecomm[j]).show();
      } else {
        $(ecomm[j]).hide();
      }
    }
    numberOfQuestions = id;
  }

  if (numberOfQuestions == 0)
    numberOfQuestions = 1;
  //Put in the answers if url includes answer=1
  if (window.location.search && /answer/.test(window.location.search)) {
    $('<def/>', {
      text: "Cheat mode",
      css: {
        position: 'fixed',
        right: '2ex',
        bottom: '2ex',
        width: '14ex',
        backgroundColor: 'yellow',
        padding: '2ex',
        textAlign: 'center'
      }
    })
      .appendTo($('body'));
    $('.quf textarea').each(function () {
      $(this).val($(this).closest('form').next('.ans').text());
    })
  }

  //Fill in default answers
  if (wgUserName && wgPageName) {
    $.getJSON('/userData.php', {
      action: 'getMostRecent',
      wgUserName: wgUserName,
      wgPageName: wgPageName
    }, function (d) {
      for (var i = 0; i < d.ret.length; i++) {
        var q = d.ret[i].question.split('#');
        q = q[1].replace('.', '');
        var ta = $('#frm__' + q + ' div.quf textarea');
        ta.val(d.ret[i].txt);
        if (d.ret[i].score == 100)
          showCorrect($('#frm__' + q).parents('.qu,.ht,.err'));
      }
    });
  }
})
//Find the text that best matches the specified engine
//The div.qu (or div.ht) node contains a number of div.def
//These may be specific to an engine in which case they have one or more e-sqlserver e-mysql classes
function findBestDefText(quNode, engine) {
  var def = $('.def', quNode);
  if (def.length == 0) return "";
  if (def.length == 1) return def.text();
  var perfect = $('.def.e-' + engine)
  if (perfect.length == 1) return perfect.text();
  for (var k = 0; k < def.length; k++) {
    if (!$(def[k]).attr('class').match('e-'))
      return $(def[k]).text();
  }
  //No match found, no default found - just give the first one
  return $(def[0]).text();
}

function updateProgressbar() {
  var numberOfQuestions = parseInt(localStorage.getItem(wgPageName + '_numberOfQuestions'));
  // A little workaround - max progressbar width is 96% of its background's width
  var maxWidth = parseFloat($('.progressbarbg').css('width')) * 0.96;
  //var barModification = parseFloat($('.progressbar').css('width')) + maxWidth * 1 / numberOfQuestions;
  //$('.progressbar').css('width', barModification);
  numberOfCorrect = $('div.qcorrect').length;
  numberOfAnswered = $('div.qincorrect').length;
  numberOfQuestions = $('div.qu').length;

  // Display completion info
  $(".summary").html("There are " + numberOfQuestions + " questions on this page.<br/>" + numberOfCorrect + " of your answers were correct.");
  // Save completion info
  if (localStorage) {
    localStorage.setItem(wgPageName + '_numberOfQuestions', numberOfQuestions);
    localStorage.setItem(wgPageName + '_numberOfCorrect', numberOfCorrect);
  }

  //Progress bar

  var barModification = parseFloat(maxWidth) * numberOfCorrect / numberOfQuestions;
  $('.progressbar').css('width', barModification);

  //Progress bar in main menu
  var maxWidth = 50;
  pbWidth = parseFloat(maxWidth) * numberOfCorrect / numberOfQuestions;

  var barId = $('#firstHeading').text().replace(/\ /g, '_');
  $("#" + barId).css('width', pbWidth);

  //Progress bars in Path Menu
  var wikipage = window.location.pathname;
  wikipage = wikipage.split('/');

  var path = $('#pathNameTxt').text();
  var NoCs = 0;
  var NoQs = 0;

  $.getJSON('/userData.php', {
    action: 'updProgressBar',
    wgUserName: wgUserName,
    path: path,
    wikipage: wikipage[2]
  }, function (d) {
    if (d.corr.length > 0) {
      NoCs = parseInt(d.corr[0].correct);
      NoQs = parseInt(d.maxCor[0].maxCor);
      var maxWidthp = 29.2;
      var pbWidthp = parseFloat(maxWidthp) * NoCs / NoQs;
      $('#' + wikipage[2]).css('width', pbWidthp);
    }
  });
}

function goBaby() {
  var qu = $(this).parents('.qu, .ht, .err');
  var lsUse = ((qu[0].getAttribute('class') != 'ht') && ($(qu[0]).find('.ans').length > 0));
  var lsName = wgPageName + '_' + $(this).parents('form').attr('id');
  var sql = qu.find('textarea.sql').val();
  var parlst = $('.params').text().split(';');
  var params = {};
  for (var i = 0; i < parlst.length; i++) {
    var pair = parlst[i].split(':');
    params[pair[0]] = pair[1];
  }
  qu.find('.res').addClass('waiting');
  $.ajax({
    url: '/sqlgo.pl',
    cache: false,
    'type': 'post',
    dataType: 'json',
    data: {
      sql: sql.replace(/\xA0/g, ' '), //Mediawiki inserts &nbsp; before a %. We need to change it back to a space.CM 13/6/12
      format: 'json',
      question: $('.id', qu).text(),
      wgUserName: wgUserName,
      cookie: $.cookie('oliver'),
      page: wgPageName,
      server: $('#engine').val(),
      setup: $('.setup', qu).text().replace(/\xA0/g, ' '),
      tidy: $('.tidy', qu).text().replace(/\xA0/g, ' '),
      answer: $('.ans', qu).text().replace(/\xA0/g, ' '),
      schema: params['schema']
    },
    success: function (d) {
      var res = qu.find('.res');
      res.empty().removeClass('waiting')
      if (d.error) {
        res.append($('<h1/>', {
          text: 'SQLZoo System Error:'
        }))
        res.append($('<div/>', {
          text: d.error
        }))
        return;
      }
      var headerPresent = false;
      for (var i = 0; i < d.sql.length; i++) {
        if (!d || !d.sql || !d.sql[i]) {
          res.append($('<h1/>', {
            text: 'SQLZoo System Error:'
          }))
          res.append($('<div/>', {
            text: "Problem with d or d.sql or d.sql[0]"
          }))
          return;
        }
        if (d.sql[i].error) {
          res.append($('<h1/>', {
            text: 'Error:'
          }))
          res.append($('<div/>', {
            text: d.sql[i].error
          }))
          return;
        }
        var legend = "Result:";
        if (d.score && d.answer && d.answer.length == 1 && d.answer[0].fields) {
          if (d.score == 100)
            legend = showCorrect(qu);
          else if (d.answer[0].fields.length > d.sql[0].fields.length)
            legend = 'Too few columns';
          else if (d.answer[0].fields.length < d.sql[0].fields.length)
            legend = 'Too many columns';
          else if (d.answer[0].rows.length > d.sql[0].rows.length)
            legend = 'Too few rows';
          else if (d.answer[0].rows.length < d.sql[0].rows.length)
            legend = 'Too many rows';
        }
        if (!headerPresent) {
          res.append($('<h1/>', {
            text: legend
          }));
          headerPresent = true;
        }
        var t = mkTable(d.sql[i]);
        t.addClass('sqlmine')
          .appendTo(res);
        if (d.answer && d.answer.length > 0 && d.score < 100) {
          res.append($('<div/>', {
              text: 'Show correct result',
              'class': 'showtxt'
            })
            .click(function () {
              $(this).next().show('slow');
            })
          );
          var a = mkTable(d.answer[0]);
          a.addClass('sqlans');
          a.appendTo(res);
        }
      } //End of success of goBaby
    },
    error: function (jqXHR, textStatus, errorThrown) {
      qu.find('.res').empty().removeClass('waiting')
        .append($('<h1/>', {
          'class': 'syserr',
          text: 'SQLZOO system error:'
        }))
        .append($('<div/>', {
          text: textStatus
        }))
        .append($('<div/>', {
          text: errorThrown
        }))
        .append($('<div/>').html(jqXHR.responseText))
    }
  });
  return false;
} //GoBaby
function showCorrect(qu) {
  var legend = 'Correct answer';
  var def = $('.def', qu);
  if ($(".qcorrect", qu).length == 0) {
    var qcorr = $('<div/>', {
      'class': 'qcorrect',
      'title': 'You have answered this question correctly.'
    });
    def.before(qcorr);
  }
  return legend;
}

function maxlen(l) {
  var r = 0;
  for (var i = 0; i < l.length; i++)
    r = Math.max(r, l[i].length);
  return r;
}

function truncate(s) {
  if (s.length < 15) return s;
  return s.substring(0, 13) + "..";
}

function mkTable(a) {
  var t = $('<table/>');
  t.append($('<tr/>'));
  var isnum = [];
  if (!a.fields || !a.rows) return t;
  for (var i = 0; i < a.fields.length; i++) {
    $('tr', t).append($('<th/>', {
      text: truncate(a.fields[i])
    }));
    var allNum = 1;
    for (var j = 0; j < a.rows.length; j++) {
      if (a.rows[j] && a.rows[j][i] && (typeof a.rows[j][i] == "string") && !a.rows[j][i].match(/^[0-9.]*$/))
        allNum = 0;
    }
    isnum.push(allNum);
  }
  for (var j = 0; j < a.rows.length; j++) {
    var tr = $('<tr/>').appendTo(t);
    for (var k = 0; k < a.rows[j].length; k++) {
      var td = $('<td/>', {
        text: a.rows[j][k]
      });
      if (isnum[k]) td.addClass('r');
      td.appendTo(tr);
    }
  }
  return t;
}

/* ======================= Designer js starts here */

// the following scripts should not be present here - they should be linked as external files !!!

// this script helps to manage browser inconsistency across different os
function css_browser_selector(u) {
  var ua = u.toLowerCase(),
    is = function (t) {
      return ua.indexOf(t) > -1
    }, g = 'gecko',
    w = 'webkit',
    s = 'safari',
    o = 'opera',
    m = 'mobile',
    h = document.documentElement,
    b = [(!(/opera|webtv/i.test(ua)) && /msie\s(\d)/.test(ua)) ? ('ie ie' + RegExp.$1) : is('firefox/2') ? g + ' ff2' : is('firefox/3.5') ? g + ' ff3 ff3_5' : is('firefox/3.6') ? g + ' ff3 ff3_6' : is('firefox/3') ? g + ' ff3' : is('gecko/') ? g : is('opera') ? o + (/version\/(\d+)/.test(ua) ? ' ' + o + RegExp.$1 : (/opera(\s|\/)(\d+)/.test(ua) ? ' ' + o + RegExp.$2 : '')) : is('konqueror') ? 'konqueror' : is('blackberry') ? m + ' blackberry' : is('android') ? m + ' android' : is('chrome') ? w + ' chrome' : is('iron') ? w + ' iron' : is('applewebkit/') ? w + ' ' + s + (/version\/(\d+)/.test(ua) ? ' ' + s + RegExp.$1 : '') : is('mozilla/') ? g : '', is('j2me') ? m + ' j2me' : is('iphone') ? m + ' iphone' : is('ipod') ? m + ' ipod' : is('ipad') ? m + ' ipad' : is('mac') ? 'mac' : is('darwin') ? 'mac' : is('webtv') ? 'webtv' : is('win') ? 'win' + (is('windows nt 6.0') ? ' vista' : '') : is('freebsd') ? 'freebsd' : (is('x11') || is('linux')) ? 'linux' : '', 'js'];
  c = b.join(' ');
  h.className += ' ' + c;
  return c;
};
css_browser_selector(navigator.userAgent);

// display site description
// display site logo
$(function () {
  $('<a/>', {
    href: 'http://sqlzoo.net/w/index.php',
    id: 'mp-logo',
    title: 'Zoo You!'
  })
    .append($('<img/>', {
      id: 'logoImg',
      src: '/design/sql_zoo_logo05.png',
      alt: 'SQLzoo logo'
    }))
    .appendTo($('#mw-head-base'))
  $("#mp-logo").append(" <h2 id='logo-desc'>Interactive <span>SQL <span>Tutorial</span></span></h2>");
  // assemble and display main nav menu
  //Original dropdown menu starts from here or backup common.js_29.7.13.js
  var ml1 = [
    ['1 SELECT basics', 'SELECT_basics', 'Some simple queries to get you started'],
    ['2 SELECT from WORLD', 'SELECT_from_WORLD_Tutorial', 'Finding facts about countries'],
    ['3 SELECT from Nobel', 'SELECT_from_Nobel_Tutorial', 'More practice with SELECT statements'],
    ['4 SELECT within SELECT', 'SELECT_within_SELECT_Tutorial', 'Using the results of one query inside another'],
    ['5 SUM and COUNT', 'SUM_and_COUNT', 'Apply aggregate functions'],
    ['6 JOIN', 'The_JOIN_operation', 'Gathering data from more than one table'],
    ['7 More JOIN', 'More_JOIN_operations', 'Getting data from the movie database'],
    ['8 Using NULL', 'Using_Null', 'Dealing with missing data'],
    ['9 Self JOIN', 'Self_join', 'Dealing with missing data'],
    ['10 SQL Quizzes', 'Tutorial_Quizzes', 'Test your knowledge with multiple choice quizzes']
  ];
  var mm1 = $('<ul/>', {
    id: 'mm1',
    'class': 'dropdown mm_sub'
  });
  //Adding progress bar to the menu
  for (var i = 0; i < ml1.length; i++) {
    var maxWidth = 50; // adjust the value so that it fits menu well
    var NoQ = localStorage.getItem(ml1[i][1] + '_numberOfQuestions');
    if (!NoQ) NoQ = 1;
    var NoC = localStorage.getItem(ml1[i][1] + '_numberOfCorrect');
    if (!NoC) NoC = 0;
    var pbWidth = parseFloat(maxWidth) * NoC / NoQ;
    var mprogbar = $('<div/>', {
      'class': 'progressbarbg1'
    }).append($('<div/>', {
      'class': 'progressbar1',
      'id': ml1[i][1],
      css: {
        'width': pbWidth
      }
    }));
    mm1.append($('<li/>').append($('<a/>', {
      href: '/wiki/' + ml1[i][1],
      text: ml1[i][0]
    })).append(ml1[i][2]).append(mprogbar));
  }
  var mm2 = $('<ul/>', {
    id: 'mm2',
    'class': 'dropdown mm_sub'
  });
  mm2.append('<li><a href="/wiki/AdventureWorks">1 AdventureWorks</a> Flogging sports gear. Assessment for CO22008 2007/8</li>');
  mm2.append('<li><a href="/wiki/Neeps">2 Neeps</a> A timetable database</li>');
  mm2.append('<li><a href="/wiki/Musicians">3 Musicians</a> Concerts and compositions</li>');
  mm2.append('<li><a href="/wiki/Southwind">4 Southwind</a> Buying and selling</li>');
  mm2.append('<li><a href="/wiki/Dressmaker">5 Dressmaker</a> Constructing clothing</li>');
  mm2.append('<li><a href="/wiki/Congestion Charging">6 Congestion Charging</a> Monitoring traffic (old questions)</li>');

  var mm3 = $('<ul/>', {
    id: 'mm3',
    'class': 'dropdown mm_sub'
  });
  mm3.append('<li><a href="/wiki/SELECT_Reference">SELECT</a>How to read the data from a database.</li>');
  mm3.append('<li><a href="/wiki/CREATE_and_DROP_Reference">CREATE and DROP</a>How to create tables, indexes, views and other things. How to get rid of them.</li>');
  mm3.append('<li><a href="/wiki/INSERT_and_DELETE_Reference">INSERT and DELETE</a>How to put records into a table, change them and how to take them out again.</li>');
  mm3.append('<li><a href="/wiki/DATE_and_TIME_Reference">DATE and TIME</a>How to work with dates; adding, subtracting and formatting.</li>');
  mm3.append('<li><a href="/wiki/Functions_Reference">Functions</a>How to use string functions, logical functions and mathematical functions.</li>');
  mm3.append('<li><a href="/wiki/Users_Reference">Users</a>How to create users, GRANT and DENY access, get at other peoples tables. How to find processes and kill them.</li>');
  mm3.append('<li><a href="/wiki/Meta_Data_Reference">Meta Data</a>How to find out what tables and columns exist. How to count and limit the rows return.</li>');
  mm3.append('<li><a href="/wiki/Hacks_Reference">SQL Hacks</a>Useful SQL hacks .</li>');

  var mm = $('<ul/>', {
    id: 'main_menu'
  }).appendTo('#mw-head-base');
  mm.append('<li id="mm1"><a href="/" class="navlink">Tutorials</a></li>');
  mm.append('<li id="mm2"><a href="/" class="navlink">Assessments</a></li>');
  mm.append('<li id="mm3"><a href="/" class="navlink">Reference</a></li>');

  $('#mm1').append(mm1);
  $('#mm2').append(mm2);
  $('#mm3').append(mm3);

  $('#main_menu').wrap('<div id="navigation_horiz" />');
  $('#navigation_horiz').naviDropDown({
    dropDownWidth: '35em'
  });

  // stick on top elements that need to be visible
  $("#main_menu").addClass("stickableMenu");
  $("#p-Reference").addClass("stickableRef");
  $(".ref_section").addClass("stickableDbRef");
});

$(document).scroll(function () {
  var useFixedMenu = $(document).scrollTop() > 175;
  $('.stickableMenu').toggleClass('fixedMenu', useFixedMenu);

  //    var useFixedRef = $(document).scrollTop() > 275;
  //    $('.stickableRef').toggleClass('fixedRef', useFixedRef);

  $('.stickableDbRef').toggleClass('fixedDbRef', $(document).scrollTop() > 275);
});

// swap classes on external links to change their side icons
$(function () {
  $(".external").addClass("zoo_external");
  $(".external").removeClass("external");
});

//Deal with variations in the "how to" scripts
$(function(){
  $('div.ht').each(function(){
    var lnglst = $('.def',this);
    var varlst = [];
    if (lnglst.length>1){
      $('.def',this).each(function(){
        var clist = $(this).attr('class').split(' ');
        for(var i=0;i<clist.length;i++)
          if (clist[i].length>2 && clist[i].substr(0,2)=='e-')
            varlst.push({engine:clist[i].substr(2),val:$(this)})
      });
    }
    if (varlst.length>0){
      var msg = $('<div/>',{'class':'variations'});
      msg.append($('<div/>',{text:'There are variations'}));
      for(var i=0;i<varlst.length;i++){
        $('<div/>',{'class':'a-'+varlst[i].engine})
          .append($('<div/>',{text:varlst[i].engine}))
          .append(varlst[i].val.clone().removeClass('def'))
          .appendTo(msg);
      }
    }
    $(this).append(msg);
  })
})

$(function () {
  // Insert tables into Quiz distractors
  var qq = $('.question');
  for (var i = 0; i < qq.length; i++) {
    var q = $(qq[i]);
    var distractors = "ABCDE";
    for (var j = 0; j < distractors.length; j++) {
      var tans = $('table caption:contains("Table-' + distractors[j] + '")', q).parents('table').addClass('innerTable');
      if (tans.length > 0) {
        var ttd = $('table tr td:contains("Table-' + distractors[j] + '")', q).addClass('innerTable');
        if (ttd.length > 0) {
          ttd.empty();
          ttd.append(tans);
        }
      }
    }
  }
  $('input.check').each(function (i, e) {
    $(this).attr('id', 'quiz_d_' + i);
  });
  $('tr.proposal').each(function (i, e) {
    var tds = $('td', $(this));
    var html1 = $(tds[1]).html();
    $(tds[1]).html($('<label/>', {
      html: html1,
      'for': $('input', $(this)).attr('id')
    }));
  })

  // Find labels which contains "query-" to add class for css
  $("label:contains('Query-')").addClass('labelAns'); //contains Query-
  $(".quizQuestions table.object tr.proposal label").not($('.quizQuestions table.object tr.proposal label div.mw-geshi').parent()).not($('.quizQuestions table.object tr.proposal label table.innerTable').parent()).addClass('labelAns');

  // Find radioButtons with title="Right" to highlight the Right results in Quizzes
  $("input:radio[title=Right]").each(function () {
    $(this).parents(':eq(1)').children().addClass("right");
  });

  // Find radioButtons with title="Wrong" to highlight the Right results in Quizzes
  $("input:radio[title=Wrong][checked=checked]").each(function () {
    $(this).parents(':eq(1)').children().addClass("wrong");
  });

  $("input:radio[title=Wrong]").not(':checked').each(function () {
    $(this).parents(':eq(1)').children().addClass("right");
  })
})

$(function () {
  // Toggle Class when the answer is selected - Istvan
  $('.quizQuestions table.object tr.proposal').click(function () {
    var rbtnId = $(this).find('label').attr('for');
    if ($("#" + rbtnId).attr("checked") == "checked") {
      $(this).children().addClass('picked');
      //Store data in QUIZLOG table
      var cookie = wgUserName;
      var question = $(this).parents('.question').children('.header').text().split('.'); //question[0]
      question = question[0].replace(/[^a-z0-9,]/gi, '');
      question = wgPageName + "#" + question + ".";
      var txt = $(this).children('.sign.picked').children().val().split(""); //p0-A; p1-B; p2-C; p3-D; p4-E;
      var results = ["A", "B", "C", "D", "E"];
      txt = results[txt[1]];
      var score = null;
      $.getJSON('/userData.php', {
        action: 'recordChoice',
        wikipage: wgPageName,
        cookie: wgUserName,
        question: question,
        txt: txt,
        score: score,
        user: wgUserName
      }, function (e) {})
    }
    $(".check").not(':checked').parent().parent().children().removeClass("picked");
  });

  var quiz = window.location.href.split("#");

  var indx = $.inArray("quiz0", quiz);
  if (indx > -1) {
    //Selecting input checked and answered right 
    wikipage = wgPageName;
    cookie = wgUserName;
    //whn = now();
    var results = ["A", "B", "C", "D", "E"];
    score = "100";
    user = wgUserName;
    var questionArr = [];
    var txtArr = [];

    $('.quizQuestions table.object tr.proposal').children('.sign.right').children('input[type=radio]:checked').each(function () {

      question = $(this).attr('name').split(""); //question[1] this is the question number on the wikipage
      question = parseInt(question[1]) + 1;
      question = wgPageName + "#" + question + ".";
      txt = $(this).val().split(""); //This is the answer
      txt = results[txt[1]];

      txtArr.push(txt);
      questionArr.push(question);

    });
    var txtStr = JSON.stringify(txtArr);
    var questStr = JSON.stringify(questionArr);
    txtStr = txtStr.replace(/[^a-z0-9,_#.]/gi, '');
    questStr = questStr.replace(/[^a-z0-9,_#.]/gi, '');
    $.getJSON('/userData.php', {
      action: 'updateAnswers',
      wikipage: wgPageName,
      cookie: wgUserName,
      question: questStr,
      txt: txtStr,
      score: score,
      user: wgUserName
    }, function (e) {
      console.log("cool");
    })
    console.log("end");
  }
})

// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend(jQuery.easing, {
  def: 'easeOutQuad',
  swing: function (x, t, b, c, d) {
    //alert(jQuery.easing.default);
    return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
  },
  easeInQuad: function (x, t, b, c, d) {
    return c * (t /= d) * t + b;
  },
  easeOutQuad: function (x, t, b, c, d) {
    return -c * (t /= d) * (t - 2) + b;
  },
  easeInOutQuad: function (x, t, b, c, d) {
    if ((t /= d / 2) < 1) return c / 2 * t * t + b;
    return -c / 2 * ((--t) * (t - 2) - 1) + b;
  },
  easeInCubic: function (x, t, b, c, d) {
    return c * (t /= d) * t * t + b;
  },
  easeOutCubic: function (x, t, b, c, d) {
    return c * ((t = t / d - 1) * t * t + 1) + b;
  },
  easeInOutCubic: function (x, t, b, c, d) {
    if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
    return c / 2 * ((t -= 2) * t * t + 2) + b;
  },
  easeInQuart: function (x, t, b, c, d) {
    return c * (t /= d) * t * t * t + b;
  },
  easeOutQuart: function (x, t, b, c, d) {
    return -c * ((t = t / d - 1) * t * t * t - 1) + b;
  },
  easeInOutQuart: function (x, t, b, c, d) {
    if ((t /= d / 2) < 1) return c / 2 * t * t * t * t + b;
    return -c / 2 * ((t -= 2) * t * t * t - 2) + b;
  },
  easeInQuint: function (x, t, b, c, d) {
    return c * (t /= d) * t * t * t * t + b;
  },
  easeOutQuint: function (x, t, b, c, d) {
    return c * ((t = t / d - 1) * t * t * t * t + 1) + b;
  },
  easeInOutQuint: function (x, t, b, c, d) {
    if ((t /= d / 2) < 1) return c / 2 * t * t * t * t * t + b;
    return c / 2 * ((t -= 2) * t * t * t * t + 2) + b;
  },
  easeInSine: function (x, t, b, c, d) {
    return -c * Math.cos(t / d * (Math.PI / 2)) + c + b;
  },
  easeOutSine: function (x, t, b, c, d) {
    return c * Math.sin(t / d * (Math.PI / 2)) + b;
  },
  easeInOutSine: function (x, t, b, c, d) {
    return -c / 2 * (Math.cos(Math.PI * t / d) - 1) + b;
  },
  easeInExpo: function (x, t, b, c, d) {
    return (t == 0) ? b : c * Math.pow(2, 10 * (t / d - 1)) + b;
  },
  easeOutExpo: function (x, t, b, c, d) {
    return (t == d) ? b + c : c * (-Math.pow(2, -10 * t / d) + 1) + b;
  },
  easeInOutExpo: function (x, t, b, c, d) {
    if (t == 0) return b;
    if (t == d) return b + c;
    if ((t /= d / 2) < 1) return c / 2 * Math.pow(2, 10 * (t - 1)) + b;
    return c / 2 * (-Math.pow(2, -10 * --t) + 2) + b;
  },
  easeInCirc: function (x, t, b, c, d) {
    return -c * (Math.sqrt(1 - (t /= d) * t) - 1) + b;
  },
  easeOutCirc: function (x, t, b, c, d) {
    return c * Math.sqrt(1 - (t = t / d - 1) * t) + b;
  },
  easeInOutCirc: function (x, t, b, c, d) {
    if ((t /= d / 2) < 1) return -c / 2 * (Math.sqrt(1 - t * t) - 1) + b;
    return c / 2 * (Math.sqrt(1 - (t -= 2) * t) + 1) + b;
  },
  easeInElastic: function (x, t, b, c, d) {
    var s = 1.70158;
    var p = 0;
    var a = c;
    if (t == 0) return b;
    if ((t /= d) == 1) return b + c;
    if (!p) p = d * .3;
    if (a < Math.abs(c)) {
      a = c;
      var s = p / 4;
    } else var s = p / (2 * Math.PI) * Math.asin(c / a);
    return -(a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p)) + b;
  },
  easeOutElastic: function (x, t, b, c, d) {
    var s = 1.70158;
    var p = 0;
    var a = c;
    if (t == 0) return b;
    if ((t /= d) == 1) return b + c;
    if (!p) p = d * .3;
    if (a < Math.abs(c)) {
      a = c;
      var s = p / 4;
    } else var s = p / (2 * Math.PI) * Math.asin(c / a);
    return a * Math.pow(2, -10 * t) * Math.sin((t * d - s) * (2 * Math.PI) / p) + c + b;
  },
  easeInOutElastic: function (x, t, b, c, d) {
    var s = 1.70158;
    var p = 0;
    var a = c;
    if (t == 0) return b;
    if ((t /= d / 2) == 2) return b + c;
    if (!p) p = d * (.3 * 1.5);
    if (a < Math.abs(c)) {
      a = c;
      var s = p / 4;
    } else var s = p / (2 * Math.PI) * Math.asin(c / a);
    if (t < 1) return -.5 * (a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p)) + b;
    return a * Math.pow(2, -10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p) * .5 + c + b;
  },
  easeInBack: function (x, t, b, c, d, s) {
    if (s == undefined) s = 1.70158;
    return c * (t /= d) * t * ((s + 1) * t - s) + b;
  },
  easeOutBack: function (x, t, b, c, d, s) {
    if (s == undefined) s = 1.70158;
    return c * ((t = t / d - 1) * t * ((s + 1) * t + s) + 1) + b;
  },
  easeInOutBack: function (x, t, b, c, d, s) {
    if (s == undefined) s = 1.70158;
    if ((t /= d / 2) < 1) return c / 2 * (t * t * (((s *= (1.525)) + 1) * t - s)) + b;
    return c / 2 * ((t -= 2) * t * (((s *= (1.525)) + 1) * t + s) + 2) + b;
  },
  easeInBounce: function (x, t, b, c, d) {
    return c - jQuery.easing.easeOutBounce(x, d - t, 0, c, d) + b;
  },
  easeOutBounce: function (x, t, b, c, d) {
    if ((t /= d) < (1 / 2.75)) {
      return c * (7.5625 * t * t) + b;
    } else if (t < (2 / 2.75)) {
      return c * (7.5625 * (t -= (1.5 / 2.75)) * t + .75) + b;
    } else if (t < (2.5 / 2.75)) {
      return c * (7.5625 * (t -= (2.25 / 2.75)) * t + .9375) + b;
    } else {
      return c * (7.5625 * (t -= (2.625 / 2.75)) * t + .984375) + b;
    }
  },
  easeInOutBounce: function (x, t, b, c, d) {
    if (t < d / 2) return jQuery.easing.easeInBounce(x, t * 2, 0, c, d) * .5 + b;
    return jQuery.easing.easeOutBounce(x, t * 2 - d, 0, c, d) * .5 + c * .5 + b;
  }
});

/**
 * hoverIntent r5 // 2007.03.27 // jQuery 1.1.2+
 * <http://cherne.net/brian/resources/jquery.hoverIntent.html>
 *
 * @param  f  onMouseOver function || An object with configuration options
 * @param  g  onMouseOut function  || Nothing (use configuration options object)
 * @author    Brian Cherne <brian@cherne.net>
 */
(function ($) {
  $.fn.hoverIntent = function (f, g) {
    var cfg = {
      sensitivity: 7,
      interval: 100,
      timeout: 0
    };
    cfg = $.extend(cfg, g ? {
      over: f,
      out: g
    } : f);
    var cX, cY, pX, pY;
    var track = function (ev) {
      cX = ev.pageX;
      cY = ev.pageY;
    };
    var compare = function (ev, ob) {
      ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
      if ((Math.abs(pX - cX) + Math.abs(pY - cY)) < cfg.sensitivity) {
        $(ob).unbind("mousemove", track);
        ob.hoverIntent_s = 1;
        return cfg.over.apply(ob, [ev]);
      } else {
        pX = cX;
        pY = cY;
        ob.hoverIntent_t = setTimeout(function () {
          compare(ev, ob);
        }, cfg.interval);
      }
    };
    var delay = function (ev, ob) {
      ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
      ob.hoverIntent_s = 0;
      return cfg.out.apply(ob, [ev]);
    };
    var handleHover = function (e) {
      var p = (e.type == "mouseover" ? e.fromElement : e.toElement) || e.relatedTarget;
      while (p && p != this) {
        try {
          p = p.parentNode;
        } catch (e) {
          p = this;
        }
      }
      if (p == this) {
        return false;
      }
      var ev = jQuery.extend({}, e);
      var ob = this;
      if (ob.hoverIntent_t) {
        ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
      }
      if (e.type == "mouseover") {
        pX = ev.pageX;
        pY = ev.pageY;
        $(ob).bind("mousemove", track);
        if (ob.hoverIntent_s != 1) {
          ob.hoverIntent_t = setTimeout(function () {
            compare(ev, ob);
          }, cfg.interval);
        }
      } else {
        $(ob).unbind("mousemove", track);
        if (ob.hoverIntent_s == 1) {
          ob.hoverIntent_t = setTimeout(function () {
            delay(ev, ob);
          }, cfg.timeout);
        }
      }
    };
    return this.mouseover(handleHover).mouseout(handleHover);
  };
})(jQuery);

(function ($) {

  $.fn.naviDropDown = function (options) {

    //set up default options 
    var defaults = {
      dropDownClass: 'dropdown', //the class name for your drop down
      dropDownWidth: 'auto', //the default width of drop down elements
      slideDownEasing: 'easeInOutCirc', //easing method for slideDown
      slideUpEasing: 'easeInOutCirc', //easing method for slideUp
      slideDownDuration: 500, //easing duration for slideDown
      slideUpDuration: 500, //easing duration for slideUp
      orientation: 'horizontal' //orientation - either 'horizontal' or 'vertical'
    };

    var opts = $.extend({}, defaults, options);

    return this.each(function () {
      var $this = $(this);
      $this.find('.' + opts.dropDownClass).css('width', opts.dropDownWidth).css('display', 'none');

      var buttonWidth = $this.find('.' + opts.dropDownClass).parent().width() + 'px';
      var buttonHeight = $this.find('.' + opts.dropDownClass).parent().height() + 'px';
      if (opts.orientation == 'horizontal') {
        //$this.find('.'+opts.dropDownClass).css('left', '0px').css('top', buttonHeight);
      }
      if (opts.orientation == 'vertical') {
        $this.find('.' + opts.dropDownClass).css('left', buttonWidth).css('top', '0px');
      }

      $this.find('li').hoverIntent(getDropDown, hideDropDown);
    });

    function getDropDown() {
      activeNav = $(this);
      showDropDown();
    }

    function showDropDown() {
      activeNav.find('.' + opts.dropDownClass).slideDown({
        duration: opts.slideDownDuration,
        easing: opts.slideDownEasing
      });
    }

    function hideDropDown() {
      activeNav.find('.' + opts.dropDownClass).slideUp({
        duration: opts.slideUpDuration,
        easing: opts.slideUpEasing
      }); //hides the current dropdown
    }

  };
})(jQuery);

//**************** USERACCOUNT.JS STARTS HERE ****************//
var docTitle = "User:" + wgUserName + " - SQLZOO";
if (document.title === docTitle) {
  function ownStat() {
    $.getJSON('/userData.php', {
      action: 'userStuff',
      wgUserName: wgUserName
    }, function (d) {

      if (d.cohort.length > 0) { //Check if user is member of any cohort

        var createUserStat = $('<div/>', {
          id: 'divUserStat'
        });
        $('#content_2').append(createUserStat);

        $('#divUserStat')
          .append($('<div/>', {
            id: 'userContHeader'
          }))
          .append($('<div/>', {
            id: 'userCont'
          }));
        $('#userContHeader')
          .append($('<div/>', {
            id: 'divStatHeader'
          }))

        function emptyUserCont() {
          $('#userCont')
            .empty()
            .append($('<div/>', {
              id: 'divMeTable'
            }))
            .append($('<div/>', {
              id: 'questCont'
            }))
            .append($('<div/>', {
              id: 'answCont'
            }))
            .append($('<div/>', {
              id: 'divClear2'
            }));
        }

        var id = "0";
        //Adding myself to an existing cohort 
        $('#divStatHeader').append($('<div/>', {
          id: 'addMeCont'
        }));
        var textAddMe = "<span id='addMeTxt'>Add me to an existing Cohort</span>";
        var inputAddMe = $('<input/>', {
          id: 'inputAddMe'
        });
        var btnAddMe = $('<div/>', {
          id: 'btnAddme',
          text: 'AddMe'
        });
        var warnTxt = $('<div/>', {
          id: 'warnText',
          text: "Cohort doesn't exists!"
        });
        $('#addMeCont').append(textAddMe);
        $('#addMeCont').append($('<div/>', {
          id: 'ibAddMe'
        })).append($('<div/>', {
          'class': 'divClear'
        }));
        $('#ibAddMe').append(inputAddMe).append(btnAddMe).append(warnTxt).hide();
        $('#addMeTxt').click(function () {
          $('#ibAddMe').slideToggle("100");
          $('#btnAddme').live('click', function () {
            var mem = wgUserName;
            var cohortName = $('#inputAddMe').val();

            $.getJSON('/userData.php', {
              action: 'addStudent',
              cohortName: cohortName,
              mem: mem
            }, function (b) {
              //b.noCohort="1" - no cohort in db

              if (b.noCohort == "0") {
                $('#warnText').hide();
                $('#inputAddMe').val("");
                window.location.reload(true);
              } else {
                $('#warnText').show();
              }
            });
            $('#inputAddMe').val("");
          });
        });

        //Adding cohort selector buttons to the header
        function cohortSelectors() {
          $('#divCoSelCont').empty();
          for (var i = 0; i < d.cohort.length; i++) {
            var cohort = d.cohort[i].id;
            var path = d.path[i][0].pathName;
            var divCoSelect = $('<div/>', {
              'class': 'btnCoSelect',
              id: i,
              text: cohort + ' ' + ' ' + path
            });
            var divSep = $('<div/>', {
              'class': 'divSeparator'
            });
            $('#divStatHeader').append($('<div/>', {
              id: 'divCoSelCont'
            }));
            $('#divCoSelCont').append(divCoSelect).append(divSep);

          }
          $('#0').addClass('btnCoSelectPicked');
          var divClear = $('<div/>', {
            'class': 'divClear'
          });
          $('#divStatHeader').append(divClear);
        }

        cohortSelectors();

        //Click function on cohort selector buttons
        $('div.btnCoSelect').click(function () {
          $('div.btnCoSelect').removeClass('btnCoSelectPicked');
          $(this).addClass('btnCoSelectPicked');
          id = $(this).attr('id');
          tutquizArrays(id);
          pageLoad(id);
        });

        //Pushing the questions in an array
        var allquestArr = [];
        var allansArr = [];

        function tutquizArrays(id) {
          allquestArr = [];
          $.each(d.questions[id], function (i, quest) {
            allquestArr.push({
              quest: d.questions[id][i].quest,
              flag: d.questions[id][i].flag
            });
          });
          allansArr = [];
          $.each(d.answ[id], function (i, answ) {
            allansArr.push({
              answ: d.answ[id][i].txt,
              whn: d.answ[id][i].whn,
              score: d.answ[id][i].score
            });
          });
        }

        //Create attempt divs
        function answCreateFunc(allansArr) {
          if (allansArr.length == 0) {
            $('#answCont').hide();
          } else {
            if (!$('#answContHeader').hasClass('answContHead')) {
              $('#answCont').append($('<div/>', {
                id: 'answContHeader',
                'class': 'answContHead'
              }));
              $('#answCont').append($('<div/>', {
                id: 'answContAnsw'
              }));
              $('#answContHeader').append('<span id="spanAnTxt">ATTEMPTS</span>');
            }
            //Quizzes
            if ($('.pickedTut').hasClass('quizRowUsr')) {
              $('.questClass').removeClass('pickedQuest');

              $('#answContAnsw').slideUp(500, function () {
                $('#answContAnsw').empty();
                for (var j = 0; j < allansArr.length; j++) {
                  var datime = allansArr[j].whn;
                  datime = datime.split(" ");
                  var divDatime = $('<div/>', {
                    'class': 'classDatime'
                  }).append(allansArr[j].whn);
                  var divAnsAns = $('<div/>', {
                    'class': 'classAnsAns'
                  }).append(allansArr[j].answ);
                  var answ = $('<div/>', {
                    id: 'answ' + j,
                    'class': 'answQuizClass'
                  }).append(divDatime).append(divAnsAns);
                  var score = parseInt(allansArr[j].score);
                  $('#answContAnsw').append(answ);
                  if (score === 100) {
                    $('#answ' + j).addClass('goodAnsw');
                  }
                }
              });
              $('#answCont').animate({
                width: "185px",
                opacity: "show"
              }, 500, "linear", function () {
                $('#answContAnsw').slideDown(500);
              });
            } else {
              //Tutorials
              //runs when clicking from Quiz to Tutorial
              if ($('#answContAnsw').children().hasClass('answQuizClass')) {
                $('#answCont').css({
                  'width': 'auto'
                });

                $('#answContAnsw').slideUp(500, function () {
                  $('#answContAnsw').empty();
                  for (var j = 0; j < allansArr.length; j++) {
                    var datime = allansArr[j].whn;
                    datime = datime.split(" ");
                    var answ = $('<div>', {
                      id: 'answ' + j,
                      'class': 'answClass'
                    }).append(allansArr[j].whn).append("<pre class='brush:[sql]'>" + allansArr[j].answ + "</pre>");
                    var score = parseInt(allansArr[j].score);
                    $('#answContAnsw').append(answ);
                    if (score === 100) {
                      $('#answ' + j).addClass('goodAnsw');
                    }
                  }
                  $('#answCont').show();
                  var x = $('#answContAnsw').css('width'); //get the new width
                  $('#answCont').css({
                    'width': '185px'
                  }); //set the previous width
                  $('#answCont').animate({
                    width: x
                  }, 500, function () {
                    $('#answContAnsw').slideDown(500);
                  });
                });
              } else if ($('#answContAnsw').children().hasClass('answClass')) {
                //@reload runs this
                $('#answCont').show();
                $('#answCont').css({
                  'width': 'auto'
                });
                var origX = $('#answContAnsw').css('width');
                $('#answContAnsw').slideUp(500, function () {
                  $('#answContAnsw').empty().hide();
                  for (var j = 0; j < allansArr.length; j++) {
                    var datime = allansArr[j].whn;
                    datime = datime.split(" ");
                    var answ = $('<div>', {
                      id: 'answ' + j,
                      'class': 'answClass'
                    }).append(allansArr[j].whn).append("<pre class='brush:[sql]'>" + allansArr[j].answ + "</pre>");
                    var score = parseInt(allansArr[j].score);
                    $('#answContAnsw').append(answ);
                    if (score === 100) {
                      $('#answ' + j).addClass('goodAnsw');
                    }
                  }
                  var x = $('#answContAnsw').css('width');
                  $('#answCont').css({
                    'width': origX
                  });
                  $('#answCont').animate({
                    width: x
                  }, 500, function () {
                    $('#answContAnsw').slideDown(500);
                  });

                });
              } else {
                //runs at first load
                $('#answCont').css({
                  'width': 'auto'
                });
                $('#answContAnsw').hide();
                for (var j = 0; j < allansArr.length; j++) {
                  var datime = allansArr[j].whn;
                  datime = datime.split(" ");
                  var answ = $('<div>', {
                    id: 'answ' + j,
                    'class': 'answClass'
                  }).append(allansArr[j].whn).append("<pre class='brush:[sql]'>" + allansArr[j].answ + "</pre>");
                  var score = parseInt(allansArr[j].score);
                  $('#answContAnsw').append(answ);
                  if (score === 100) {
                    $('#answ' + j).addClass('goodAnsw');
                  }
                }
                var x = $('#answContAnsw').css('width');
                $('#answCont').css({
                  'width': '0'
                });
                $('#answCont').animate({
                  width: x
                }, 500, function () {
                  $('#answContAnsw').slideDown(500);
                });
              }
            }
          }
        }

        //Create questions divs
        function questCreateFunc(allquestArr) {
          $('#questCont').append($('<div/>', {
            id: 'questClassCont'
          }));
          $('#questClassCont').slideUp(500, function () {
            $('#questClassCont').empty();
            for (var j = 0; j < allquestArr.length; j++) {
              var quest = $('<div>', {
                id: 'quest' + j,
                'class': 'questClass',
                text: allquestArr[j].quest
              });

              if (allquestArr[j].flag == "q") {
                $('#questClassCont').append(quest);
                $('#quest' + j).addClass('questQuizClass');
              } else {
                $('#questClassCont').append(quest);
                $('#quest' + j).addClass('questTutClass');

              }
            }
            $('#quest0.questTutClass').addClass('pickedQuest');
            $('#questClassCont').delay(0).slideDown(500);
          })
        }

        function pageLoad(id) {
          emptyUserCont();
          //Tutorials and quizzes table
          var table = $('<table/>', {
            id: 'statMeTable'
          });
          var trh = $('<tr/>', {
            id: 'tHeaderMeUsr'
          }).append('<th><span>TUTORIALS & QUIZZES</span></th><th><span>SCORES</span></th><th><span>RESULTS</span></th>');
          table.append(trh);
          for (var i = 0; i < d.tut[id].length; i++) {
            var tr = $('<tr/>');

            if (d.tut[id][i].flag == 't') {
              var tr = $('<tr/>', {
                'class': 'tutsRowUsr',
                id: d.tut[id][i].wikipage
              }); //.addClass('tutsClass');
            } else {
              var tr = $('<tr/>', {
                'class': 'quizRowUsr',
                id: d.tut[id][i].wikipage
              }); //.addClass('quizClass');
            }
            var td = $('<td/>');
            var te = $('<td/>');
            var tf = $('<td/>');
            td.append(d.tut[id][i].sname);
            var resultObj = [];
            resultObj = getObjects(d.statMe[id], 'Tutorials', d.tut[id][i].sname);
            if (resultObj.length == 0) {
              var res = '';
              te.append(res);
              tf.append(res);
              tr.append(td).append(te).append(tf).addClass('statMeRows');
              table.append(tr);

            } else {
              te.append(resultObj[0].Score);
              tf.append(resultObj[0].Results);
              tr.append(td).append(te).append(tf).addClass('statMeRows');
              table.append(tr);
            }

          }

          $('#divMeTable').append(table);
          $('#statMeTable tr:nth-child(2)').addClass('pickedTut');

          //questions
          $('#questCont').append($('<div/>', {
            id: 'questContHeader'
          }));
          $('#questContHeader').append('<span id="spanQuTxt">QUESTIONS</span>');

          questCreateFunc(allquestArr);

          //Answers

          answCreateFunc(allansArr);
        }

        tutquizArrays(id);
        pageLoad(id);

        //Refresh answers
        function refansFunc(quest) {
          var wikipage = $('.pickedTut').attr('id'); //for quizzes
          if ($('.pickedTut').hasClass('quizRowUsr')) {
            //WIKIPAGE
            $.getJSON('/userData.php', {
              action: 'answersQuiz',
              wikipage: wikipage,
              wgUserName: wgUserName
            }, function (f) {
              allansArr = [];
              $.each(f.answ, function (i, answ) {
                allansArr.push({
                  answ: f.answ[i].txt,
                  whn: f.answ[i].whn,
                  score: f.answ[i].score
                });
              });

              answCreateFunc(allansArr);
            });
          } else {
            $.getJSON('/userData.php', {
              action: 'answersTut',
              quest: quest,
              wgUserName: wgUserName
            }, function (f) {
              allansArr = [];
              $.each(f.answ, function (i, answ) {
                allansArr.push({
                  answ: f.answ[i].txt,
                  whn: f.answ[i].whn,
                  score: f.answ[i].score
                });
              });
              answCreateFunc(allansArr);
            });
          }
        }

        //Click on Tutorial or Quiz

        $('#statMeTable tr.statMeRows').live('click', function () {
          $('#statMeTable tr.statMeRows').removeClass('pickedTut');
          $(this).addClass('pickedTut');
          path = d.path[id][0].pathName;

          var tutName = $(this).find(':first-child').text();
          $.getJSON('/userData.php', {
            action: 'quest',
            path: path,
            tutName: tutName
          }, function (e) {
            allquestArr = [];
            $.each(e.quest, function (i, quest) {
              allquestArr.push({
                quest: e.quest[i].quest,
                flag: e.quest[i].flag
              });
            });
            questCreateFunc(allquestArr);
            var quest = allquestArr[0].quest;
            refansFunc(quest);
          });
        });

        //Click event for only Tutorial Questions
        $('div.questTutClass').live('click', function () {
          $('div.questTutClass').removeClass('pickedQuest');
          $(this).addClass('pickedQuest');
          var quest = $(this).text();
          refansFunc(quest);
        });
      }
    });
  }

  ////////////////////////////*****TEACHERS STUFF STARTS HERE*****/////////////////////////////////  

  if ($.inArray('teacher', wgUserGroups) > -1) {

    $(function () {
      var popupDiv = $('<div/>', {
        id: 'divPopup'
      })
      $('#bodyContent').prepend(popupDiv);
      $('#divPopup').hide();

      //Create Tab structure
      $('#bodyContent').prepend($('<div/>', {
        id: 'tab_box_1',
        'class': 'tabbed_box'
      }));
      $('#tab_box_1').append($('<div/>', {
        id: 'tab_area',
        'class': 'tabbed_area'
      }));

      $('#tab_area').append($('<ul/>', {
        id: 'ulTabs',
        'class': 'tabs'
      }));
      $('#ulTabs').append("<li><a href='#' id='tab_1' title='content_1' class='tab active'>Cohort Stats</a></li>");
      $('#ulTabs').append("<li><a href='#' id='tab_2' title='content_2' class='tab' >Own Stat</a></li>");

      $('#tab_area').append($('<div/>', {
        id: 'content_1',
        'class': 'content'
      }));
      $('#tab_area').append($('<div/>', {
        id: 'content_2',
        'class': 'content'
      }));

      ownStat();

      // When a link is clicked  
      $("a.tab").click(function () {
        // switch all tabs off  
        $(".active").removeClass("active");
        // switch this tab on  
        $(this).addClass("active");
        // slide all elements with the class 'content' up  
        $(".content").slideUp();
        $('#answCont').css({
          'width': 'auto'
        });

        // Now figure out what the 'title' attribute value is and find the element with that id.  Then slide that down.  
        var content_show = $(this).attr("title");
        $("#" + content_show).slideDown();
      });

      //**************createCohort append to TAB 1 (#content_1)

      var createCohort = $('<div/>', {
        id: 'divCohort'
      });
      $('#content_1').append(createCohort);

      //Creating Cohort Selector  
      var pathDb = $('<select/>', {
        id: 'selectPath',
        title: 'Select path',
        'class': 'btnClass'
      }); //Getting the path id from Db and store in this array
      $.getJSON('/userData.php', {
        action: 'getPathDb'
      }, function (d) {
        for (var i = 0; i < d.pathDb.length; i++) {
          var optionList = $('<option/>', {
            val: i,
            text: d.pathDb[i].pathId
          });
          pathDb.append(optionList);
        }
      });

      //Create buttons and input field
      $('#divCohort')
        .append($('<div>', {
          id: 'divCohortHeader'
        }))
        .append($('<div/>', {
          id: 'cohorCont'
        }))
        .append($('<div/>', {
          id: 'cohortContStat'
        }))
        .append($('<div/>', {
          id: 'memberCont'
        }))
        .append($('<div/>', {
          id: 'divClear'
        }));

      $('#divCohortHeader')
        .append($('<input/>', {
          'type': 'text',
          id: 'newCohortName',
          'class': 'btnClass',
          name: 'newCohortName',
          title: 'Type a cohort name',
          required: 'required'
        }))
        .append(pathDb)
        .append($('<input/>', {
          'type': 'button',
          value: 'Add Cohort',
          'class': 'btnClass',
          click: addCohorts
        }))
        .append($('<input/>', {
          'type': 'button',
          value: 'Rename Cohort',
          id: 'btnEdCo',
          'class': 'btnClass',
          click: editCohorts
        }))
        .append($('<input/>', {
          'type': 'button',
          value: '% or Score',
          id: 'btnStatCo',
          'class': 'btnClass',
          click: changeView
        }))
        .append($('<input/>', {
          'type': 'button',
          value: 'Add Students',
          id: 'btnAddStu',
          'class': 'btnClass',
          click: addStudents
        }))
        .append($('<input/>', {
          'type': 'button',
          value: 'Delete Student',
          id: 'btnDelStu',
          'class': 'btnClass',
          click: delStudents
        }));
      $('#btnDelCo').hide();
      $('#btnEdCo').hide();
      $('#btnStatCo').hide();
      $('#btnAddStu').hide();
      $('#btnDelStu').hide();
      $('#memberCont').hide();
      updateCohorts();

      //Unselect cohort when clicked somewhere else
      $(document).click(function (e) {
        if ($(e.target).is('#cohorCont, #cohorCont *, #cohortContStat, #cohortContStat *, #divPopup, #divPopup *,.btnClass, #newCohortName, #memberCont, #memberCont *')) {
          return;
        } else {
          $('.cohortClass').removeClass('pickedCo');
          $('#newCohortName').val('');

          $('#cohortContStat').slideUp(500, function () {
            $(this).empty();
          });
          $('#memberCont').slideUp(500, function () {
            $(this).empty();
          });
          $('#btnDelCo').hide();
          $('#btnStatCo').hide();
          $('#btnAddStu').hide();
          $('#btnDelStu').hide();
          $('#btnEdCo').hide();
        }
      });
    });

    //Add cohorts to list and DB
    function addCohorts() {
      if ($('#newCohortName').val() == "") {
        alert("Type cohort name!")
      } else {
        var selVal = $('#selectPath').val();
        var selPath = $('#selectPath option[value=' + selVal + ']').text();
        $.getJSON('/userData.php', {
          action: 'addCohort',
          wgUserName: wgUserName,
          cohortName: $('#newCohortName').val(),
          selPath: selPath
        }, function (d) {
          updateCohorts();
        });
      }
    }

    //Updating the list of cohorts from DB  
    function updateCohorts() {
      $('#cohorCont').slideUp(300);
      $('#cohorCont').slideDown(300, function () {
        $('#cohorCont').empty();
        $.getJSON('/userData.php', {
          action: 'listTeachersCohorts',
          wgUserName: wgUserName
        }, function (d) {
          for (var i = 0; i < d.cohorts.length; i++) {
            var cohortList = $('<div/>', {
              'class': 'cohortClass',
              id: 'divCo' + i,
              click: clickListCo
            })
              .append("<span class='cohortTxtClass'>" + d.cohorts[i].id + "</span>")
              .append("<img src='/design/bin.png' name='divCo" + i + "' onClick=binClick(name) class='imgBin' title='Delete Cohort' alt='22'>")
              .append("<span class='pathNameClass'>" + d.cohorts[i].pathName + "</span>");
            $('#cohorCont').append(cohortList);
          }
          if ($('#cohorCont>div').length > 0) {
            $('#cohorCont div:nth-child(1)').trigger('click');
          }

        });
        $('#newCohortName').val("");
        $('#memberCont').empty().hide();
      });
    }

    //Delete cohorts from list and DB
    function deleteCohorts() {
      var id = $('.pickedCo .cohortTxtClass').text();

      $.getJSON('/userData.php', {
        action: 'delCohort',
        wgUserName: wgUserName,
        cohortName: id
      }, function (d) {

      });
      updateCohorts();
    }

    //Edit cohorts name  cohortClass
    function editCohorts() {
      var prevCo = $('.pickedCo .cohortTxtClass').text();
      var newCo = $('#newCohortName').val();

      $.getJSON('/userData.php', {
        action: 'editCohort',
        oldCohort: prevCo,
        cohortName: newCo
      }, function (d) {

      });
      updateCohorts();
    }

    //Click event on Cohort list 
    function clickListCo() {
      $('.cohortClass').removeClass('pickedCo');
      $(this).addClass('pickedCo');
      $('#btnDelCo').show();
      $('#btnStatCo').show();
      $('#btnAddStu').show();
      $('#btnDelStu').show();
      $('#btnEdCo').show();
      $('#memberCont').slideUp(500, function () {
        $(this).empty();
      });
      var cohortId = $('.pickedCo .cohortTxtClass').text();
      $('#newCohortName').val(cohortId);
      cohortStat();
    }

    //Update Member's list 
    function updateMembers() {
      $('#cohortContStat').empty();
      var cohortId = $('.pickedCo .cohortTxtClass').text();
      $.getJSON('/userData.php', {
        action: 'listMembers',
        cohortName: cohortId
      }, function (d) {

      });
    }

    //Click event on Member(student)
    function clickMem() {
      $('.memberClass').removeClass('pickedMe');
      $(this).addClass('pickedMe');
    }

    //Member(student) statistics  
    function studentStat() {

      $('#memberCont').slideUp(500);
      var member = $('.pickedMe').text();
      var path = $('.pickedCo .pathNameClass').text();
      $('#memberCont').empty();
      //Getting stat from DB
      $.getJSON('/userData.php', {
        action: 'statMember',
        path: path,
        member: member
      }, function (d) {
        var table = $('<table/>', {
          id: 'statMeTable'
        });
        var trh = $('<tr/>', {
          id: 'tHeaderMe'
        }).append('<th><span>TUTORIALS<br>AND<br>QUIZZES</span></th><th><span>SCORE</span></th><th><span>RESULTS</span></th>');
        table.append(trh);
        for (var i = 0; i < d.tut.length; i++) {

          if (d.tut[i].flag == "q") {
            var tr = $('<tr/>', {
              'class': 'quizRow'
            });
          } else {
            var tr = $('<tr/>', {
              'class': 'tutRow'
            });
          }
          var td = $('<td/>');
          var te = $('<td/>');
          var tf = $('<td/>');
          td.append(d.tut[i].sname)
          var resultObj = [];
          resultObj = getObjects(d.statMe, 'Tutorials', d.tut[i].sname);
          if (resultObj.length == 0) {
            var res = '';
            te.append(res);
            tf.append(res);
            tr.append(td).append(te).append(tf);
            table.append(tr);
          } else {
            te.append(resultObj[0].Score);
            tf.append(resultObj[0].Results);

            tr.append(td).append(te).append(tf);
            table.append(tr);
          }
        }

        $('#memberCont').append($('<div/>', {
          id: 'memContHeader'
        })).append($('<div/>', {
          id: 'divMeTable'
        }));
        $('#divMeTable').append(table);
        $('#memberCont').slideDown(500);
      });
    }

    //Add students to a cohort
    function addStudents() {
      if ($('.cohortClass').hasClass('pickedCo')) {
        var id = $('.pickedCo .cohortTxtClass').text();
        var members = [];
        $('body').append($('<div/>', {
          'class': 'overlay'
        }));
        $('#divPopup').show();
        $('#divPopup').append($('<input/>', {
          'type': 'button',
          value: 'x',
          id: 'btnClosePopup',
          'class': 'btnClass',
          click: function () {
            $('.overlay').remove();
            $('#divPopup').hide().empty();
          }
        }))
          .append($('<div/>', {
            id: 'popupCont'
          }))
          .append($('<input/>', {
            'type': 'button',
            value: 'OK',
            id: 'btnOkPopup',
            'class': 'btnClass',
            click: function () {
              //Popup OK button stuff 
              members = $('#txtPopup').val();
              $.getJSON('/userData.php', {
                action: 'addStudent',
                wgUserName: wgUserName,
                cohortName: id,
                mem: members
              }, function (d) {
                //cohortStat();
              });
              $('.overlay').remove();
              $('#divPopup').hide().empty();
              cohortStat();
            }
          }));
        $('#popupCont').append($('<textarea/>', {
          id: 'txtPopup',
          rows: '10',
          cols: '10'
        }));
      } else {
        alert('Select a cohort!');
      }
    }

    function delStudents() {
      var stu = $('.pickedMe').text();
      var cohort = $('.pickedCo .cohortTxtClass').text();
      $('body').append($('<div/>', {
        'class': 'overlay'
      }));
      $('#divPopup').show().addClass('dialog');
      $('#divPopup').append($('<input/>', {
        'type': 'button',
        value: 'x',
        id: 'btnClosePopup',
        'class': 'btnClass',
        click: function () {
          $('.overlay').remove();
          $('#divPopup').removeClass('dialog').hide().empty()
        }
      }))
        .append($('<div/>', {
          id: 'textCont'
        }))
        .append($('<div/>', {
          id: 'popupCont'
        }))

      var btn = $('<input/>', {
        'type': 'button',
        value: 'Yes',
        id: 'btnOkPopup',
        'class': 'btnClass',
        click: function () {
          //Popup - YES button stuff  
          $.getJSON('/userData.php', {
            action: 'delStudent',
            student: stu,
            cohort: cohort
          }, function (d) {});
          $('.overlay').remove();
          $('#memberCont').hide().empty();
          cohortStat();
          $('#divPopup').removeClass('dialog').hide().empty()
        }
      })
      btn.appendTo('#popupCont');

      btn = $('<input/>', {
        'type': 'button',
        value: 'No',
        id: 'btnNoPop',
        'class': 'btnClass',
        click: function () {
          $('.overlay').remove();
          $('#divPopup').removeClass('dialog').hide().empty()
        }
      });
      btn.appendTo('#popupCont');

      var textCont = $('<span/>', {
        id: 'spanNoti',
        text: 'Do you really want to remove student from cohort?'
      })
      textCont.appendTo('#textCont');
    }

    //Getting Cohort statistics from DB 
    function cohortStat() {
      var cohort = $('.pickedCo .cohortTxtClass').text();
      var path = $('.pickedCo .pathNameClass').text();
      if ($('#cohortContStat').children().length > 0) {
        $('#cohortContStat').slideUp(500, function () {
        });
      }

      //Getting stat from DB

      $.getJSON('/userData.php', {
        action: 'statCohort',
        cohortName: cohort,
        pathId: path
      }, function (d) {
        if (d.stu.length > 0) {
          $('#cohortContStat').empty();
          var table = $('<table/>', {
            id: 'statCoTable'
          });
          //Create the header of the table
          var trh = $('<tr/>', {
            id: 'tHeader'
          }).append("<th><span>Members</span></th>");
          for (var i = 0; i < d.tut.length; i++) {
            var th = $('<th/>');
            if (d.tut[i].flag == "q") {
              var aa = $('<span/>', {
                'class': "thQuizzies"
              });
              aa.append(d.tut[i].sname);
            } else {
              var aa = $('<span/>');
              aa.append(d.tut[i].sname);
            }
            th.append(aa);
            trh.append(th);
            table.append(trh);
          }

          //Adding data to table
          for (var n = 0; n < d.stu.length; n++) {
            var tr = $('<tr/>');
            var name = d.stu[n].mem;
            var ta = $('<td/>');
            ta.append(name);
            tr.append(ta);
            var nameObj = [];
            nameObj = getObjects(d.statCo, 'member', name); //Contains all results for 1 person
            for (var j = 0; j < d.tut.length; j++) {
              var tb = $('<td/>');
              resultObj = []; //Contains result for a specific tutorial for 1 person
              resultObj = getObjects(nameObj, 'Tutorials', d.tut[j].sname);
              var ab = $('<span/>');
              var ac = $('<span/>');
              var perc = "";
              var score = "";
              if (resultObj.length == 0) { //If there is no result:
                perc = '';
                score = '';
                ab.append(perc).addClass('perc');
                ac.append(score).addClass('score')
                tb.append(ab).append(ac);
                tr.append(tb);
              } else {
                perc = resultObj[0].Results;
                score = resultObj[0].Score;
                ab.append(perc).addClass('perc');
                ac.append(score).addClass('score')
                tb.append(ab).append(ac);
                tb.append(ab).append(ac);
                tr.append(tb);
              }
              table.append(tr);
            }
          }

          $('#cohortContStat').append($('<div/>', {
            id: 'divTableHeader'
          })).append($('<div/>', {
            id: 'divTable'
          }));

          $('#divTable').append(table);
          $('.score').addClass('displayNone');
          colorCode();

          $('#cohortContStat').delay(100).slideDown(500);
          //Student stat when clicking on student
          $('#statCoTable tr td:first-child').addClass('memberClass');
          $('#statCoTable tr td:first-child').click(function () {
            $('.memberClass').removeClass('pickedMe');
            $(this).addClass('pickedMe');
            studentStat();
          })
        }
      });
    }
    //Color table cells depending on results
    function colorCode() {
      $('#statCoTable tr td').not(':first-child').each(function () {
        var res = $(this).find('.perc').html();
        res = parseInt(res);
        if (res == "") {
          $(this).addClass('none')
        } else {
          if (res === 100) {
            $(this).addClass('perfect')
          } else if ((79 < res) && (res < 100)) {
            $(this).addClass('good')
          } else if ((49 < res) && (res < 80)) {
            $(this).addClass('medium')
          } else if (res << 50) {
            $(this).addClass('lazy')
          }
        }
      });
    }

    //Change data appearence 
    function changeView() {
      $('.perc').toggleClass('displayNone');
      $('.score').toggleClass('displayNone');
    }

    //Delete cohorts from list when clicking on img bin.
    function binClick(name) {

      $('#divPopup').show().addClass('dialog');
      $('#divPopup').append($('<input/>', {
        'type': 'button',
        value: 'x',
        id: 'btnClosePopup',
        'class': 'btnClass',
        click: function () {
          $('#divPopup').removeClass('dialog').hide().empty()
        }
      }))
        .append($('<div/>', {
          id: 'textCont'
        }))
        .append($('<div/>', {
          id: 'popupCont'
        }))

      var btn = $('<input/>', {
        'type': 'button',
        value: 'Yes',
        id: 'btnOkPopup',
        'class': 'btnClass',
        click: function () {
          //Popup OK button stuff 
          $('#' + name).addClass('pickedCo');
          deleteCohorts();
          $('#divPopup').removeClass('dialog').hide().empty()
        }
      })
      btn.appendTo('#popupCont');

      btn = $('<input/>', {
        'type': 'button',
        value: 'No',
        id: 'btnNoPop',
        'class': 'btnClass',
        click: function () {
          $('#divPopup').removeClass('dialog').hide().empty()
        }
      });
      btn.appendTo('#popupCont');

      var textCont = $('<span/>', {
        id: 'spanNoti',
        text: 'Do you really want to delete this cohort?'
      })
      textCont.appendTo('#textCont');
      $('#cohortContStat').hide().empty();
    }
    //////////////////*******Stuffs for 'user' *******////////////////////////
  } else if ($.inArray('user', wgUserGroups) == 1) {
    $(function () {
      $('#bodyContent').prepend($('<div/>', {
        id: 'content_2'
      }));
      ownStat();
      $('#content_2').show();
    })
  }
}
/* MediaWiki:Vector.js */
/* Any JavaScript here will be loaded for users using the Vector skin */
;
mw.loader.state({"site":"ready"});
