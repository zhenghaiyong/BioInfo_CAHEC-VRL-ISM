// $Id: notify.1.js 142376 2008-10-06 16:46:44Z sponomar $
/*===============================================================================================
File: Notifier (notifier.1.js)
Message broker
*/
 
/*===============================================================================================
Method: Notifier()
Constructor.

Arguments:
None.

Return:
None.
*/
function Notifier() {
    this.oQuee = new Array;
    this.bTraceOn = false;
}

/*===============================================================================================
Method: setListener()
Set listener.

Arguments:
oListener - (Object) Object who set this listener.
sMessage - (String) Message. 
fFunction (Function) Callback function.
oNotifier (Object) Instance of notifier who handled the message.

Return:
None.
*/
Notifier.prototype.setListener = function(oListener, sMessage, fFunction, oNotifier) {
    var oThis = this;
    if (utils.isArray(oListener)) {
        for (var i in oListener) {
            x_setListener(oListener[i], sMessage, fFunction, oNotifier);
        }
    } else {
        return x_setListener(oListener, sMessage, fFunction, oNotifier);
    }

    function x_setListener(oListener, sMessage, fFunction, oNotifier) {
        for (var i in oThis.oQuee[sMessage]) {
            if (oThis.oQuee[sMessage][i].obj === oListener) {
                if (oListener.NAME) console.info("Reset setListener [", i, "] ", oListener.NAME, sMessage);
                oThis.oQuee[sMessage][i] = ({obj:oListener, fun:fFunction, ntf:oNotifier});
                return true;
            }
        }
        if (!oThis.oQuee[sMessage]) oThis.oQuee[sMessage] = new Array();
        var i = oThis.oQuee[sMessage].length;   //IE5.0 + better performance than using 'push'
        if (oThis.bTraceOn) console.info("setListener:", sMessage, "["+ i + "]: ", oListener.NAME);
        oThis.oQuee[sMessage][i] = ({obj:oListener, fun:fFunction, ntf:oNotifier});
        return true;
    }
}


/*===============================================================================================
Method: Notify()
Fire message.

Arguments:
oNotifier - (Object) Instance of notifier who fired the message.
sMessage - (String) Message.
oComment - (Object) Any additional data complement given message. 
oListener - (Object) Object who listen to. If null, all object will be notified.

Return:
None.
*/
Notifier.prototype.Notify = function(oNotifier, sMessage, oComment, oListener) {
    var oThis = this;
    
    var sAnyMessage = "*";
    for (var i in this.oQuee[sAnyMessage]) {
        if (null == oListener || this.oQuee[sAnyMessage][i].obj == oListener) {
            if (this.oQuee[sAnyMessage][i].ntf == null || this.oQuee[sAnyMessage][i].ntf === oNotifier) {
                if ("function" == typeof this.oQuee[sAnyMessage][i].fun)
                    this.oQuee[sAnyMessage][i].fun(this.oQuee[sAnyMessage][i].obj, oComment, sMessage, oNotifier);
            }
        }
    }

    
    for (var i in this.oQuee[sMessage]) {
        if (null == oListener || this.oQuee[sMessage][i].obj == oListener) {
            if (this.oQuee[sMessage][i].ntf == null || this.oQuee[sMessage][i].ntf === oNotifier) {
                if ("function" == typeof this.oQuee[sMessage][i].fun) {
                    if (oThis.bTraceOn) console.info("Notify:", sMessage, "["+ i + "]: ", this.oQuee[sMessage][i].obj.NAME);
                    if (this.oQuee[sMessage][i].fun(this.oQuee[sMessage][i].obj, oComment, sMessage, oNotifier)) {
                        return;
                    }
                }
            }
        }
    }
    
}

/*===============================================================================================
Method: Clear()
Remove all registered listeners for given message.

Arguments:
sMessage - (String) Message who has to be cleaned; clean all listeners if set in "*".

Return:
None.
*/
Notifier.prototype.Clear = function(sMessage) {
    if ("*" == sMessage) {
        this.oQuee = [];
    } else {
        this.oQuee[sMessage] = [];
    }
}

/*===============================================================================================
Method: getInstance()
Singleton for global shared notifier.

Arguments:
None.

Return:
None.
*/
Notifier.getInstance = function() {
   if (!Notifier.instance) {
       Notifier.instance = new Notifier();
   }
   return Notifier.instance;
}
