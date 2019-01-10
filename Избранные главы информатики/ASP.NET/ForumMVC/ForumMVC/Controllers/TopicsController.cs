using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using ForumMVC.Models;
using PagedList.Mvc;
using PagedList;
using System.Data.Entity;

namespace ForumMVC.Controllers
{
    // КОнтроллер работы с темами
    public class TopicsController : Controller
    {
        ForumContext db = new ForumContext();

        public ActionResult Index()
        {
            return View("Error");
        }
        // Вывод списка тем
        public ActionResult List(int? id, int? page)
        {
            // Получаем запись раздела из БД
            Rubric rubric = db.Rubrics.FirstOrDefault(p => p.Id == id);
            // Если такая запись есть
            if (rubric != null)
            {
                // формируем набор данных для формы
                ViewBag.Rubric = rubric;
                IEnumerable<Topic> topics = db.Topics.Include("User").Include("Messages.User").Where(p => p.RubricId == id);
                List<TopicView> topicViewList = new List<TopicView>();
                foreach (Topic topic in topics)
                {
                    TopicView topicView = new TopicView();
                    topicView.Id = topic.Id;
                    topicView.Name = topic.Name;
                    topicView.Content = topic.Content;
                    topicView.TopicStarter = topic.User.Login;
                    int cntMsg = topic.Messages.Count;
                    if (cntMsg > 0)
                    {
                        topicView.CntMsg = cntMsg.ToString();
                        Message msg = topic.Messages.OrderByDescending(p => p.Dates).First();
                        topicView.LastWriter = msg.User.Login;
                        topicView.Dates = msg.Dates.ToString();
                    }
                    topicViewList.Add(topicView);
                }
                ViewBag.TopicCnt = topicViewList.Count;
                // параметры постраничного вывода
                int pageSize = 10;
                int pageNumber = (page ?? 1);
                // выводим представление
                return View(topicViewList.ToPagedList(pageNumber, pageSize));
            }
            else
            {
                return View("Error");
            }
        }
        // Вывод формы добавления темы
        // доступно только админу, модератору, пользователю
        [Authorize(Roles = "Admin, Moder, User")]
        public ActionResult Create(int id)
        {
            Rubric rubric = db.Rubrics.FirstOrDefault(p => p.Id == id);
            if (rubric != null)
            {
                ViewBag.Rubric = rubric;
                return View(new TopicEdit());
            }
            else
            {
                return View("Error");
            }
        }
        // Обработка формы добавления темы
        [Authorize(Roles = "Admin, Moder, User")]
        [HttpPost]
        public ActionResult Create(TopicEdit model)
        {
            if (ModelState.IsValid)
            {
                Rubric rubric = db.Rubrics.FirstOrDefault(p => p.Id == model.RubricId);
                User user = db.Users.FirstOrDefault(p => p.Login == User.Identity.Name);
                if (rubric != null && user != null)
                {
                    Topic topic = new Topic();
                    topic.Name = model.Name;
                    topic.Content = model.Content;
                    topic.Rubric = rubric;
                    topic.User = user;
                    db.Topics.Add(topic);
                    db.SaveChanges();
                    return RedirectToAction("List/" + model.RubricId);
                }
                else
                {
                    ModelState.AddModelError("", "Ошибка при сохранении");
                }
            }
            return View(model);
        }
        // Вывод формы редактирования темы
        [Authorize (Roles="Admin, Moder")]
        public ActionResult Edit(int? id)
        {
            Topic topic = db.Topics.FirstOrDefault(p => p.Id == id);
            if (topic != null)
            {
                TopicEdit model = new TopicEdit();
                model.Id = topic.Id;
                model.Name = topic.Name;
                model.RubricId = (int)topic.RubricId;
                model.Content = topic.Content;
                return View(model);
            }
            else
            {
                return View("Error");
            }
        }
        // Обработка формы редактирования темы
        [Authorize (Roles="Admin, Moder")]
        [HttpPost]
        public ActionResult Edit(TopicEdit model)
        {
            if (ModelState.IsValid)
            {
                Topic topic = db.Topics.FirstOrDefault(p => p.Id == model.Id);
                if (topic != null)
                {
                    topic.Name = model.Name;
                    topic.Content = model.Content;
                    db.Entry(topic).State = EntityState.Modified;
                    db.SaveChanges();
                    return RedirectToAction("List/" + model.RubricId);
                }
                else
                {
                    ModelState.AddModelError("", "Ошибка при сохранении");
                }
            }
            return View(model);
        }
        // Вывод формы удаления темы
        [Authorize (Roles="Admin, Moder")]
        public ActionResult Delete(int? id)
        {
            Topic topic = db.Topics.FirstOrDefault(p => p.Id == id);
            if (topic != null)
            {
                TopicEdit model = new TopicEdit();
                model.Id = topic.Id;
                model.Name = topic.Name;
                model.RubricId = (int)topic.RubricId;
                model.Content = topic.Content;
                return View(model);
            }
            else
            {
                return View("Error");
            }
        }
        // Обработка формы удаления темы
        [Authorize(Roles = "Admin, Moder")]
        [HttpPost]
        public ActionResult Delete(int? id, int? page)
        {
            Topic topic = db.Topics.FirstOrDefault(p => p.Id == id);
            if (topic != null)
            {
                int rubricId = (int)topic.RubricId;
                db.Topics.Remove(topic);
                db.SaveChanges();
                return RedirectToAction("List/" + rubricId);
            }
            else
            {
                return View("Error");
            }
        }

    }
}
