clear all;
clc;

A = [1 -3 2 0 1 -1 4 -1 0
    1 -1 6 1 0 -2 2 2 0
    2 2 -1 1 0 -3 8 -1 1
    4 1 0 0 1 -1 0 -1 1
    1 1 1 1 1 1 1 1 1];

b = [3 9 9 5 9];
c = [-1 5 -2 4 3 1 2 8 3];

lb = [0 0 0 0 0 0 0 0 0];
ub = [5 5 5 5 5 5 5 5 5];

optimalSolution = struct('m', [], 'm0', 0, 'r0', -inf(1));
stack = false;
t = 0;

task = struct('lb', lb, 'ub', ub);
while isstruct(task)
    t = t + 1;
    disp(strcat('Итерация# ', int2str(t)));
    disp('Решаем задачу ЛП с границами:');
    disp(task.lb);
    disp(task.ub);
    x0 = solveLPMax(c, A, b, task.lb, task.ub);
    if (length(x0) == 1) % plan not found
        disp('Задача ЛП не имеет решения.');
    elseif isIntegerPlan(x0) % plan exists and numeric
        disp('Решение найдено и оно целочисленное:');
        disp(x0.');
        cx = sum(x0.*c');
        if cx > optimalSolution.r0
            disp(strcat('Значение cx= ', int2str(cx), ' больше чем r0=',int2str(optimalSolution.r0), ', замещаем оптимальный да данный момент план.'));
            optimalSolution.r0 = cx;
            optimalSolution.m0 = 1;
            optimalSolution.m = x0;
        else
            disp(strcat('Значение cx= ', int2str(cx), ' меньше либо равно r0=',optimalSolution.r0, ', пропускаем этот план.'));
        end;
    else % plan exists but not numeric
        disp('Решение найдено, но оно не целочисленное:');
        disp(x0.');
        notInt = getNotIntegerVariable(x0);
        disp(strcat('Выбираем нецелочисленную переменную x',int2str(notInt.index),'=',num2str(notInt.value), ', которая в этой задаче ЛП находилась в диапазоне [', int2str(task.lb(notInt.index)), ';',int2str(task.ub(notInt.index)), ']'));
        task1 = struct('lb', task.lb, 'ub', task.ub);
        task1.ub(notInt.index) = floor(x0(notInt.index));
        task2 = struct('lb', task.lb, 'ub', task.ub);
        task2.lb(notInt.index) = floor(x0(notInt.index)) + 1;
        disp(strcat('Формируем 2 новые задачи ЛП с границами для x',int2str(notInt.index),' в [', int2str(task1.lb(notInt.index)), ';',int2str(task1.ub(notInt.index)), '] и [', int2str(task2.lb(notInt.index)), ';',int2str(task2.ub(notInt.index)), ']'));
        stack = stack_push(stack, task2);
        stack = stack_push(stack, task1);
    end;
    task = stack_pop(stack);
    if (isstruct(task))
        stack = stack.stack; % no passing by reference in MatLab...
    end;
    
    disp('==========================================================');
end;

disp('================== Ответ =================================');
disp('==========================================================');

if optimalSolution.m0 == 1
    disp('Целочисленное решение есть. При x''=');
    disp(optimalSolution.m');
    disp(strcat('Значение c''x=', int2str(optimalSolution.r0)));
else
    disp('Целочисленного решения, сожалению, нет');
end;

