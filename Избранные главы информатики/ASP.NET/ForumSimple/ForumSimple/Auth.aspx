<%@ Page Title="Авторизация" Language="C#" MasterPageFile="~/Template.Master" AutoEventWireup="true" CodeBehind="Auth.aspx.cs" Inherits="ForumSimple.Auth" %>
<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">
    <div class="row">
        <div class="col-xs-12">
            <h3>Авторизация пользователя</h3>
            <div>
                <div class="form-group">
                    <asp:Label ID="LabelLogin" runat="server" AssociatedControlID="Login" CssClass="control-label">Логин</asp:Label>
                    <asp:TextBox runat="server" ID="Login" CssClass="form-control" />
                    <span class="help-block">
                        <asp:RequiredFieldValidator runat="server" ID="reqLogin" ControlToValidate="Login" Display="Dynamic">Логин не может быть пустым</asp:RequiredFieldValidator>
                    </span>
                </div>
                <div class="form-group">
                    <asp:Label ID="LabelPassword" runat="server" AssociatedControlID="Password" CssClass="control-label">Пароль</asp:Label>
                    <asp:TextBox runat="server" ID="Password" TextMode="Password" CssClass="form-control" />
                    <span class="help-block">
                        <asp:RequiredFieldValidator runat="server" ID="reqPassword" ControlToValidate="Password" Display="Dynamic">Пароль не может быть пустым</asp:RequiredFieldValidator>
                    </span>
                </div>
                <asp:Button runat="server" ID="BtnLogin" Text="Авторизация" CssClass="btn btn-default" onclick="BtnLogin_Click" />
            </div>
            <br />
            <asp:Literal ID="LiteralResult" runat="server" />
        </div>
    </div>
</asp:Content>
