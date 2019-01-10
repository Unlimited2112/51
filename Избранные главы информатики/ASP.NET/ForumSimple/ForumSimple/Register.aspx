<%@ Page Title="Регистрация пользователя" Language="C#" MasterPageFile="~/Template.Master" AutoEventWireup="true" CodeBehind="Register.aspx.cs" Inherits="ForumSimple.Register" %>
<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">
    <div class="row">
        <div class="col-xs-12">
            <h3>Регистрация пользователя</h3>
            <div>
                <div class="form-group">
                    <asp:Label ID="LabelLogin" runat="server" AssociatedControlID="Login" CssClass="control-label">Логин</asp:Label>
                    <asp:TextBox runat="server" ID="Login" CssClass="form-control" />
                    <span class="help-block">
                        <asp:RequiredFieldValidator runat="server" ID="reqLogin" ControlToValidate="Login" Display="Dynamic">Логин не может быть пустым</asp:RequiredFieldValidator>
                        <asp:CustomValidator runat="server" ID="cstmLogin" ControlToValidate="Login" onServerValidate="cstmLogin_ServerValidate" Display="Dynamic" EnableClientScript="False">Такой логин уже существует</asp:CustomValidator>
                        <asp:RegularExpressionValidator runat="server" ID="rglrLogin" ControlToValidate="Login" ValidationExpression="^[a-zA-Z][a-zA-Z0-9-_\.]{1,20}$">Логин может включать 2-20 букв и цифр, должен начинаться с буквы</asp:RegularExpressionValidator>
                    </span>
                </div>
                <div class="form-group">
                    <asp:Label ID="LabelPassword" runat="server" AssociatedControlID="Password" CssClass="control-label">Пароль</asp:Label>
                    <asp:TextBox runat="server" ID="Password" TextMode="Password" CssClass="form-control" />
                    <span class="help-block">
                        <asp:RequiredFieldValidator runat="server" ID="reqPassword" ControlToValidate="Password" Display="Dynamic">Пароль не может быть пустым</asp:RequiredFieldValidator>
                    </span>
                </div>
                <div class="form-group">
                    <asp:Label ID="LabelPasswordConfirm" runat="server" AssociatedControlID="PasswordConfirm" CssClass="control-label">Подтверждение пароля</asp:Label>
                    <asp:TextBox runat="server" ID="PasswordConfirm" TextMode="Password" CssClass="form-control" />
                    <span class="help-block">
                        <asp:RequiredFieldValidator runat="server" ID="reqPasswrodConfirm" ControlToValidate="PasswordConfirm" Display="Dynamic">Необходимо ввести подтверждение пароля</asp:RequiredFieldValidator>
                        <asp:CompareValidator runat="server" ID="comparePassword" ControlToCompare="Password" ControlToValidate="PasswordConfirm" Display="Dynamic">Пароли должны совпадать!</asp:CompareValidator>
                    </span>
                </div>
                <asp:Button runat="server" ID="BtnRegister" Text="Регистрация" onclick="BtnRegister_Click" CssClass="btn btn-default" />
            </div>
        </div>
    </div>
</asp:Content>
