using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace ForumMVC.Models
{
    // Основня модель для БД
    public class Role
    {
        public Role()
        {
            Users = new HashSet<User>();
        }
        public int Id { get; set; }
        public string Name { get; set; }
        public string Description { get; set; }
        public virtual ICollection<User> Users { get; set; }
    }
}