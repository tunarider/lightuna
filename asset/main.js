function useConsole(root, threadUid) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', root + '/console.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json"');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.result === true) {
                    alert('성공!');
                } else {
                    alert('실패: ' + response.message);
                }
            } else {
                alert('실패...');
            }
        }
    };
    return xhr;
}

function maskResponse(root, threadUid, responseUids) {
    const threadPassword = prompt('Thread password?');
    useConsole(root, threadUid).send(JSON.stringify({
        'action': 'maskResponse',
        'payload': {
            'threadUid': threadUid,
            'responseUids': (Array.isArray(responseUids)) ? responseUids : [responseUids],
            'threadPassword': threadPassword
        }
    }));
}

function maskResponses(root, threadUid) {
    const checkResponses = document.getElementsByClassName('check_response');
    const responseUids = Array.prototype.reduce.call(
        checkResponses,
        (carry, checkResponse) => {
            if (checkResponse.checked) {
                carry.push(checkResponse.value);
                return carry;
            } else {
                return carry;
            }
        },
        []
    );
    maskResponse(root, threadUid, responseUids);
}

function unmaskResponse(root, threadUid, responseUids) {
    const threadPassword = prompt('Thread password?');
    useConsole(root, threadUid).send(JSON.stringify({
        'action': 'unmaskResponse',
        'payload': {
            'threadUid': threadUid,
            'responseUids': (Array.isArray(responseUids)) ? responseUids : [responseUids],
            'threadPassword': threadPassword
        }
    }));
}

function unmaskResponses(root, threadUid) {
    const checkResponses = document.getElementsByClassName('check_response');
    const responseUids = Array.prototype.reduce.call(
        checkResponses,
        (carry, checkResponse) => {
            if (checkResponse.checked) {
                carry.push(checkResponse.value);
                return carry;
            } else {
                return carry;
            }
        },
        []
    );
    unmaskResponse(root, threadUid, responseUids);
}

function banUserId(root, threadUid, responseUid) {
    const threadPassword = prompt('Thread password?');
    useConsole(root, threadUid).send(JSON.stringify({
        'action': 'banUserId',
        'payload': {
            'threadUid': threadUid,
            'responseUid': responseUid,
            'threadPassword': threadPassword
        }
    }));
}

function manageThread(root, boardUid, threadUid) {
    const threadPassword = prompt('Thread password?');
    const xhr = useConsole(root, threadUid);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                const response = JSON.parse(xhr.responseText);
                if (response.result === true) {
                    alert('성공!');
                    window.location.href = root + '/mthread.php/' + boardUid + '/' + threadUid;
                } else {
                    alert('실패: ' + response.message);
                }
            } else {
                alert('실패...');
            }
        }
    };
    xhr.send(JSON.stringify({
        'action': 'manageThread',
        'payload': {
            'threadUid': threadUid,
            'threadPassword': threadPassword
        }
    }));
}

document.addEventListener('DOMContentLoaded', function () {
    const serverInfo = document.getElementById('server_info');
    const baseUrl = serverInfo.dataset.baseUrl;

    function ncrToChar(text) {
        return text.replace(/&#(\d+);/gm, function (_, match) {
            return String.fromCharCode(match);
        });
    }

    function applyAnchor(el) {
        const parentElement = el.parentElement;
        el.innerHTML = el.innerHTML.replace(
            /([a-z]*)&gt;([0-9]*)&gt;([0-9]*)-?([0-9]*)/gm,
            function (match, boardUid, threadUid, responseStart, responseEnd) {
                if (boardUid === '' && threadUid === '' && responseStart === '') {
                    return match;
                }
                boardUid = (boardUid === '') ? parentElement.dataset.boardUid : boardUid;
                threadUid = (threadUid === '') ? parentElement.dataset.threadUid : threadUid;
                const inPageAnchor = 'response_' + threadUid + '_' + responseStart;
                if (responseEnd === ''
                    && document.getElementById(inPageAnchor)) {
                    return '<a href="#' + inPageAnchor + '">' + match + '</a>';
                } else {
                    return '<a href="'
                        + baseUrl
                        + '/trace.php/'
                        + boardUid
                        + '/'
                        + threadUid
                        + '/'
                        + responseStart
                        + '/'
                        + responseEnd
                        + '">'
                        + match
                        + '</a>';
                }
            }
        )
    }

    function applyLink(el) {
        el.innerHTML = el.innerHTML.replace(
            /https?:\/\/((?!www\.youtube\.com\/embed\/)(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b[-a-zA-Z0-9가-힣()@:%_\+;.~#?&//=]*)/g,
            function (match) {
                return '<a href="' + match + '">' + match + '</a>';
            }
        )
    }

    const contentForms = document.getElementsByClassName('post_form_content');
    Array.prototype.forEach.call(contentForms, function (el) {
        const defaultHeight = el.offsetHeight;
        el.addEventListener('input', function () {
            el.style.height = (el.scrollHeight < defaultHeight) ? defaultHeight + 'px' : el.scrollHeight + 'px';
        })
    });

    const nameForms = document.getElementsByClassName('post_form_name');
    Array.prototype.forEach.call(nameForms, function (el) {
        const threadUid = el.dataset.threadUid;
        el.value = sessionStorage.getItem(threadUid + '-name');
        el.addEventListener('input', function () {
            sessionStorage.setItem(threadUid + '-name', this.value);
        });
    });

    const consoleForm = document.getElementsByClassName('post_form_console');


    Array.prototype.forEach.call(consoleForm, function (el) {
        const threadUid = el.dataset.threadUid;
        el.value = sessionStorage.getItem(threadUid + '-console');

        el.addEventListener('input', function () {
            sessionStorage.setItem(threadUid + '-console', this.value);
        });

        el.addEventListener('input', function () {
            if (this.value.split('.').includes('aa')) {
                this.nextElementSibling.classList.add('mona');
            } else {
                this.nextElementSibling.classList.remove('mona');
            }
        });

        el.addEventListener('input', function () {
            if (el.value.split('.').includes('ncr')) {
                el.nextElementSibling.value = ncrToChar(el.nextElementSibling.value);

                function elementNcrToChar(evt) {
                    evt.target.value = ncrToChar(evt.target.value)
                }

                el.nextElementSibling.addEventListener('input', elementNcrToChar)
            } else {
                el.nextElementSibling.removeEventListener('input', elementNcrToChar)
            }
        });

        const evt = new Event('input');
        el.dispatchEvent(evt);
    });


    const contents = document.getElementsByClassName('content');
    Array.prototype.forEach.call(contents, applyAnchor);
    Array.prototype.forEach.call(contents, applyLink);

    const testButton = document.getElementsByClassName('post_form_test');
    Array.prototype.forEach.call(testButton, function (el) {
        const parentElement = el.parentElement.parentElement;
        const boardUid = parentElement.getElementsByClassName('post_form_board_uid')[0].value;
        const threadUid = parentElement.getElementsByClassName('post_form_thread_uid')[0].value;
        const thread = document.getElementById('thread_' + threadUid);
        const threadBody = thread.getElementsByClassName('thread_body')[0];
        el.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            const testIndicator = document.createElement('SPAN');
            testIndicator.classList.add('response_test_indicator');
            testIndicator.innerHTML = '테스트 중...';
            const userName = parentElement.getElementsByClassName('post_form_name')[0].value;
            const consoleText = parentElement.getElementsByClassName('post_form_console')[0].value;
            const content = parentElement.getElementsByClassName('post_form_content')[0].value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', baseUrl + '/console.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json"');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.result === true) {
                            Array.prototype.forEach.call(threadBody.getElementsByClassName('test_response'), el => el.remove());
                            const res = threadBody.lastElementChild;
                            const cloneRes = res.cloneNode(true);
                            threadBody.append(cloneRes);
                            cloneRes.getElementsByClassName('response_info')[0].append(testIndicator)
                            if (cloneRes.getElementsByClassName('response_mask')[0]) {
                                cloneRes.getElementsByClassName('response_mask')[0].remove();
                            }
                            cloneRes.getElementsByClassName('response_sequence')[0].innerHTML =
                                Number(cloneRes.getElementsByClassName('response_sequence')[0].innerHTML)
                                + 1;
                            cloneRes.getElementsByClassName('response_owner')[0].innerHTML = response.payload.userName;
                            cloneRes.getElementsByClassName('response_owner_id')[0].innerHTML = '(' + response.payload.userId + ')';
                            cloneRes.getElementsByClassName('response_create_date')[0].innerHTML = response.payload.createDate;
                            cloneRes.getElementsByClassName('content')[0].innerHTML = response.payload.content;
                            cloneRes.classList.add('test_response');
                            applyAnchor(cloneRes.getElementsByClassName('content')[0]);
                            applyLink(cloneRes.getElementsByClassName('content')[0]);
                        } else {
                            alert('실패: ' + response.message);
                        }
                    } else {
                        alert('실패...');
                    }
                }
            };
            xhr.send(JSON.stringify({
                'action': 'testResponse',
                'payload': {
                    'boardUid': boardUid,
                    'userName': userName,
                    'console': consoleText,
                    'content': content
                }
            }));
        });
    })


});
