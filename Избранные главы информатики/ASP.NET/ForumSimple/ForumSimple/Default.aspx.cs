using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using ForumSimple.Helpers;

namespace ForumSimple
{
    // Страница списка разделов
    public partial class Default : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
        }

        // Проверка права на добавление разделов
        protected bool AllowInsert()
        {
            if (HttpContext.Current.User.IsInRole("Admin"))
                return true;
            else
                return false;
        }
    }
}