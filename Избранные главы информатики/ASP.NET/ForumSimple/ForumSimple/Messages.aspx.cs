using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using ForumSimple.Helpers;
using System.Data;

namespace ForumSimple
{
    // Страница списка сообщений темы
    public partial class Messages : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            // Определяем авторизованного пользователя по логину
            // Помещаем идентификатор пользователя в скрытое поле страницы,
            // чтобы использовать при добавлении данных через ListView
            string login = HttpContext.Current.User.Identity.Name;
            User user = new User(login);
            hiddenUserID.Text = user.id.ToString();
            // Извлекаем из GET-параметра идентификатор темы
            // Помещаем идентификатор темы в скрытое поле страницы,
            // чтобы использовать при выборке из БД и добавлении данных через ListView
            int topicID = 0;
            try { topicID = Convert.ToInt32(Request.QueryString["TopicID"]); }
            catch { }
            hiddenTopicID.Text = topicID.ToString();
            // Получаем из БД названия темы и раздела и вставляем в соответствующие поля
            ForumDataSetTableAdapters.topicDataTableAdapter ta = new ForumDataSetTableAdapters.topicDataTableAdapter();
            DataTable dt = ta.GetData(topicID);
            string topicName = "Сообщения раздела";
            if (dt.Rows.Count > 0)
            {
                topicName = string.Format(" / {0}", dt.Rows[0]["name"]);
                linkRubric.Text = string.Format("{0}", dt.Rows[0]["rubricsName"]);
                linkRubric.NavigateUrl = string.Format("~/Topics.aspx?RubricID={0}", dt.Rows[0]["rubrics"]);
                linkRubric.Visible = true;
            }
            literalTopicName.Text = topicName;
            this.Title = topicName;
        }
        // Проверка права на добавление сообщений
        protected bool AllowInsert()
        {
            // Добавлять сообщения могут те, кто может скрыть (админ, модератор), и пользователь
            if (AllowDisabled() || HttpContext.Current.User.IsInRole("User"))
                return true;
            else
                return false;
        }
        // Проверка права на редактирование сообщения
        protected bool AllowEdit(object login, object disabled)
        {
            // Редактировать могут те, кто может скрыть
            if (AllowDisabled())
                return true;
            // Редактировать может пользователь, если это его сообщение и оно не скрыто модератором
            else if (HttpContext.Current.User.IsInRole("User") && HttpContext.Current.User.Identity.Name == login.ToString() && disabled.ToString() == "False")
                return true;
            else
                return false;
        }
        // Проверка права на скрытие сообщения
        protected bool AllowDisabled()
        {
            // Скрывать сообщения могут админ и модератор
            if (HttpContext.Current.User.IsInRole("Admin") || HttpContext.Current.User.IsInRole("Moder"))
                return true;
            else
                return false;
        }
        // Проверяет сообщение на скрытие, если скрыто - возвращает соответствующий текст
        protected string GetMessage(object message, object disabled)
        {
            if (disabled.ToString() == "True")
                return "Сообщение скрыто модератором...";
            else
                return message.ToString();
        }

    }
}