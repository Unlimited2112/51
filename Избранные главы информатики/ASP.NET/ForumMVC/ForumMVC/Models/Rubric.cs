using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.ComponentModel.DataAnnotations;

namespace ForumMVC.Models
{
    // Основная модель для БД
    public class Rubric
    {
        public Rubric()
        {
            Topics = new HashSet<Topic>();
        }

        public int Id { get; set; }
        [Required]
        [Display(Name = "Наименование")]
        public string Name { get; set; }
        public virtual ICollection<Topic> Topics { get; set; }
    }
}