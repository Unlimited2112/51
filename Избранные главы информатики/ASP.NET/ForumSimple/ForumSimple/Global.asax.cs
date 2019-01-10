using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Security;
using System.Web.SessionState;
using System.Security.Principal;

namespace ForumSimple
{
    public class Global : System.Web.HttpApplication
    {
        // По результатам авторизации сохраняем роль пользователя
        protected void Application_AuthenticateRequest(object sender, EventArgs e)
        {
            HttpApplication appl = (HttpApplication)sender;
            if (appl.Request.IsAuthenticated && appl.User.Identity is FormsIdentity)
            {
                HttpCookie authCookie = Request.Cookies[FormsAuthentication.FormsCookieName];
                if (authCookie != null)
                {
                    FormsIdentity identity = (FormsIdentity)appl.User.Identity;
                    FormsAuthenticationTicket authTicket = FormsAuthentication.Decrypt(authCookie.Value);
                    string roles = authTicket.UserData;
                    appl.Context.User = new GenericPrincipal(identity, new string[] {roles});
                }
            }
        }
    }
}